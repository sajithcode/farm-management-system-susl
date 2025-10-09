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

    public function feedType()
    {
        return $this->belongsTo(FeedType::class, 'feed_type', 'id');
    }
}
