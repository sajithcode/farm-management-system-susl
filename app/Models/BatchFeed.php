<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchFeed extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'feed_date',
        'feed_type',
        'quantity',
        'unit',
        'cost_per_unit',
        'batch_age_days',
        'notes',
        'user_id'
    ];

    protected $casts = [
        'feed_date' => 'date',
        'quantity' => 'decimal:2',
        'cost_per_unit' => 'decimal:2',
        'batch_age_days' => 'integer'
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the feed type record that matches this feed's type name
     * This is a temporary relationship until we migrate to proper foreign keys
     */
    public function feedType()
    {
        return $this->hasOne(FeedType::class, 'name', 'feed_type');
    }
}
