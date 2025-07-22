<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchSlaughter extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'slaughter_date',
        'count',
        'batch_age_days',
        'average_weight',
        'total_weight',
        'price_per_kg',
        'buyer',
        'slaughter_location',
        'notes',
        'user_id'
    ];

    protected $casts = [
        'slaughter_date' => 'date',
        'count' => 'integer',
        'batch_age_days' => 'integer',
        'average_weight' => 'decimal:2',
        'total_weight' => 'decimal:2',
        'price_per_kg' => 'decimal:2'
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
