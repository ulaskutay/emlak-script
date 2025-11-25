<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'title',
        'slug',
        'description',
        'video_url',
        'video_path',
        'type',
        'status',
        'price',
        'price_period',
        'currency',
        'city',
        'district',
        'address',
        'latitude',
        'longitude',
        'area_m2',
        'bedrooms',
        'living_rooms',
        'total_rooms',
        'balconies',
        'bathrooms',
        'floor',
        'building_age',
        'total_floors',
        'building_type',
        'heating_type',
        'furnished',
        'furnished_type',
        'balcony',
        'parking',
        'garden',
        'pool',
        'elevator',
        'security',
        'terrace',
        'inside_site',
        'balcony',
        'parking',
        'garden',
        'pool',
        'elevator',
        'tags',
        'published_at',
        'show_on_web',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'tags' => 'array',
            'furnished' => 'boolean',
            'balcony' => 'boolean',
            'parking' => 'boolean',
            'garden' => 'boolean',
            'pool' => 'boolean',
            'elevator' => 'boolean',
            'price' => 'decimal:2',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($listing) {
            if (empty($listing->slug)) {
                $listing->slug = static::generateUniqueSlug($listing->title);
            }
        });

        static::updating(function ($listing) {
            // Eğer title değiştiyse veya slug boşsa, yeni slug oluştur
            if ($listing->isDirty('title') || empty($listing->slug)) {
                $listing->slug = static::generateUniqueSlug($listing->title, $listing->id);
            }
        });

        static::saving(function ($listing) {
            if ($listing->status === 'active' && !$listing->published_at) {
                $listing->published_at = now();
            }
        });

        static::saved(function ($listing) {
            $listing->agent->updateActiveListingsCount();
        });
    }

    protected static function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)
            ->when($excludeId, fn($query) => $query->where('id', '!=', $excludeId))
            ->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(ListingPhoto::class)->orderBy('order');
    }

    public function coverPhoto()
    {
        // Use eager loaded photos if available, otherwise query
        if ($this->relationLoaded('photos')) {
            return $this->photos->where('is_cover', true)->first() 
                ?? $this->photos->first();
        }
        
        return $this->photos()->where('is_cover', true)->first() 
            ?? $this->photos()->first();
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    public function calendarEvents(): HasMany
    {
        return $this->hasMany(CalendarEvent::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        $price = number_format($this->price, 0, ',', '.');
        
        // Para birimi sembolü
        $symbol = match($this->currency) {
            'TRY' => '₺',
            'USD' => '$',
            'EUR' => '€',
            default => $this->currency,
        };
        
        if ($this->type === 'rent' && $this->price_period) {
            return "$symbol $price/{$this->price_period}";
        }
        
        return "$symbol $price";
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'sale' ? 'Satılık' : 'Kiralık';
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'active' => 'Aktif',
            'pending' => 'Beklemede',
            'sold' => 'Satıldı',
            'rented' => 'Kiralandı',
            'passive' => 'Pasif',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'active' => 'green',
            'pending' => 'yellow',
            'sold' => 'blue',
            'rented' => 'red',
            'passive' => 'gray',
            default => 'gray',
        };
    }
}

