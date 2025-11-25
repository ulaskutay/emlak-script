<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ListingPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'listing_id',
        'path',
        'is_cover',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'is_cover' => 'boolean',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($photo) {
            if ($photo->is_cover) {
                // Unset other cover photos
                static::where('listing_id', $photo->listing_id)
                    ->where('id', '!=', $photo->id ?? 0)
                    ->update(['is_cover' => false]);
            }
        });
    }

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }

    public function getUrlAttribute(): ?string
    {
        if (!$this->path) {
            return null;
        }

        // Check if file exists
        if (Storage::disk('public')->exists($this->path)) {
            return asset('storage/' . $this->path);
        }

        return null;
    }

    public function exists(): bool
    {
        return $this->path && Storage::disk('public')->exists($this->path);
    }
}

