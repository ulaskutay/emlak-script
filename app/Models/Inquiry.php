<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'listing_id',
        'customer_id',
        'name',
        'phone',
        'email',
        'message',
        'status',
        'assigned_agent_id',
    ];

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function assignedAgent(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'assigned_agent_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'new' => 'Yeni',
            'in_progress' => 'İşlemde',
            'closed' => 'Kapalı',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'new' => 'blue',
            'in_progress' => 'yellow',
            'closed' => 'green',
            default => 'gray',
        };
    }
}

