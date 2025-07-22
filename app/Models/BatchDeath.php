<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchDeath extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'death_date',
        'count',
        'batch_age_days',
        'cause',
        'notes',
        'user_id'
    ];

    protected $casts = [
        'death_date' => 'date',
        'count' => 'integer',
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
}
