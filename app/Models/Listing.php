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
        'type',
        'status',
        'price',
        'price_period',
        'currency',
        'city',
        'district',
        'address',
        'area_m2',
        'bedrooms',
        'bathrooms',
        'floor',
        'heating_type',
        'furnished',
        'tags',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'tags' => 'array',
            'furnished' => 'boolean',
            'price' => 'decimal:2',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($listing) {
            if (empty($listing->slug)) {
                $listing->slug = Str::slug($listing->title);
                
                // Ensure unique slug
                $originalSlug = $listing->slug;
                $count = 1;
                while (static::where('slug', $listing->slug)->exists()) {
                    $listing->slug = $originalSlug . '-' . $count;
                    $count++;
                }
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

