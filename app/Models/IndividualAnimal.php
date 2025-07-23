<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class IndividualAnimal extends Model
{
    use HasFactory;

    protected $fillable = [
        'animal_id',
        'animal_type',
        'date_of_birth',
        'supplier',
        'responsible_person',
        'gender',
        'status',
        'current_weight',
        'notes',
        'user_id'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'current_weight' => 'decimal:2'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function feedRecords()
    {
        return $this->hasMany(IndividualAnimalFeed::class);
    }

    public function deathRecord()
    {
        return $this->hasOne(IndividualAnimalDeath::class);
    }

    public function slaughterRecord()
    {
        return $this->hasOne(IndividualAnimalSlaughter::class);
    }

    // Calculated attributes
    public function getAgeInDaysAttribute()
    {
        return $this->date_of_birth->diffInDays(now());
    }

    public function getAgeInWeeksAttribute()
    {
        return floor($this->age_in_days / 7);
    }

    public function getAgeInMonthsAttribute()
    {
        return $this->date_of_birth->diffInMonths(now());
    }

    public function getAgeDisplayAttribute()
    {
        $days = $this->age_in_days;
        $weeks = floor($days / 7);
        $months = $this->age_in_months;
        
        if ($months > 0) {
            $remainingDays = $days % 30;
            return $months . 'm ' . $remainingDays . 'd';
        } elseif ($weeks > 0) {
            $remainingDays = $days % 7;
            return $weeks . 'w ' . $remainingDays . 'd';
        }
        return $days . ' days';
    }

    public function getStatusBadgeAttribute()
    {
        switch ($this->status) {
            case 'alive':
                return 'bg-success';
            case 'dead':
                return 'bg-danger';
            case 'slaughtered':
                return 'bg-dark';
            default:
                return 'bg-secondary';
        }
    }

    // Update status when death or slaughter is recorded
    public function markAsDead()
    {
        $this->update(['status' => 'dead']);
    }

    public function markAsSlaughtered()
    {
        $this->update(['status' => 'slaughtered']);
    }
}
