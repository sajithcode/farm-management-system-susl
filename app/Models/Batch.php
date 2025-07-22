<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'name',
        'animal_type',
        'start_date',
        'initial_count',
        'current_count',
        'supplier',
        'responsible_person',
        'notes',
        'user_id'
    ];

    protected $casts = [
        'start_date' => 'date',
        'initial_count' => 'integer',
        'current_count' => 'integer'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function feedRecords()
    {
        return $this->hasMany(BatchFeed::class);
    }

    public function deathRecords()
    {
        return $this->hasMany(BatchDeath::class);
    }

    public function slaughterRecords()
    {
        return $this->hasMany(BatchSlaughter::class);
    }

    // Calculated attributes
    public function getAgeInDaysAttribute()
    {
        return $this->start_date->diffInDays(now());
    }

    public function getAgeInWeeksAttribute()
    {
        return floor($this->age_in_days / 7);
    }

    public function getAgeDisplayAttribute()
    {
        $days = $this->age_in_days;
        $weeks = floor($days / 7);
        $remainingDays = $days % 7;
        
        if ($weeks > 0) {
            return $weeks . 'w ' . $remainingDays . 'd';
        }
        return $days . ' days';
    }

    public function getTotalDeathsAttribute()
    {
        return $this->deathRecords()->sum('count');
    }

    public function getTotalSlaughtersAttribute()
    {
        return $this->slaughterRecords()->sum('count');
    }

    public function updateCurrentCount()
    {
        // Get fresh data from database
        $totalDeaths = $this->deathRecords()->sum('count');
        $totalSlaughters = $this->slaughterRecords()->sum('count');
        
        $newCurrentCount = $this->initial_count - $totalDeaths - $totalSlaughters;
        
        $this->update(['current_count' => $newCurrentCount]);
        
        return $this;
    }
}
