<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndividualAnimalSlaughter extends Model
{
    use HasFactory;

    protected $fillable = [
        'individual_animal_id',
        'slaughter_date',
        'age_in_days',
        'live_weight',
        'dressed_weight',
        'purpose',
        'medicine_used',
        'notes',
        'user_id'
    ];

    protected $casts = [
        'slaughter_date' => 'date',
        'age_in_days' => 'integer',
        'live_weight' => 'decimal:2',
        'dressed_weight' => 'decimal:2'
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
