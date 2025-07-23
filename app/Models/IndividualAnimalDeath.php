<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndividualAnimalDeath extends Model
{
    use HasFactory;

    protected $fillable = [
        'individual_animal_id',
        'death_date',
        'age_in_days',
        'weight',
        'cause',
        'notes',
        'user_id'
    ];

    protected $casts = [
        'death_date' => 'date',
        'age_in_days' => 'integer',
        'weight' => 'decimal:2'
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
