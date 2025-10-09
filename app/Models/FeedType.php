<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'unit', // kg, bags, etc.
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function feedIns()
    {
        return $this->hasMany(FeedIn::class);
    }

    public function feedOuts()
    {
        return $this->hasMany(FeedOut::class);
    }

    public function batchFeeds()
    {
        return $this->hasMany(BatchFeed::class, 'feed_type', 'id');
    }

    public function individualAnimalFeeds()
    {
        return $this->hasMany(IndividualAnimalFeed::class, 'feed_type', 'id');
    }

    public function getCurrentStockAttribute()
    {
        $totalIn = $this->feedIns()->sum('quantity');
        $totalOut = $this->feedOuts()->sum('quantity');
        return $totalIn - $totalOut;
    }
}
