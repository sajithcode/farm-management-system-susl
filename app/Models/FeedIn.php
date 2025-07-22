<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedIn extends Model
{
    use HasFactory;

    protected $table = 'feed_ins';

    protected $fillable = [
        'date',
        'feed_type_id',
        'quantity',
        'supplier',
        'notes',
        'user_id'
    ];

    protected $casts = [
        'date' => 'date',
        'quantity' => 'decimal:2'
    ];

    public function feedType()
    {
        return $this->belongsTo(FeedType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
