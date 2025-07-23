<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndividualAnimalFeed extends Model
{
    use HasFactory;

    protected $fillable = [
        'individual_animal_id',
        'feed_date',
        'feed_type',
        'quantity',
        'unit',
        'cost_per_unit',
        'notes',
        'administered_by',
        'user_id'
    ];

    protected $casts = [
        'feed_date' => 'date',
        'quantity' => 'decimal:2',
        'cost_per_unit' => 'decimal:2'
    ];

    // Relationships
    public function individualAnimal()
    {
        return $this->belongsTo(IndividualAnimal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
