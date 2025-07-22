<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedOut extends Model
{
    use HasFactory;

    protected $table = 'feed_outs';

    protected $fillable = [
        'date',
        'feed_type_id',
        'quantity',
        'issued_to',
        'location',
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
