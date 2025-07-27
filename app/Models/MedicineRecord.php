<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MedicineRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'medicine_date',
        'apply_to',
        'animal_id',
        'batch_id',
        'medicine_name',
        'quantity',
        'unit',
        'cost_per_unit',
        'administered_by',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'medicine_date' => 'date',
        'quantity' => 'decimal:2',
        'cost_per_unit' => 'decimal:2',
    ];

    /**
     * Get the user who recorded this medicine.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the batch if this medicine was applied to a batch.
     */
    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    /**
     * Get the individual animal if this medicine was applied to an individual animal.
     */
    public function individualAnimal()
    {
        return $this->belongsTo(IndividualAnimal::class, 'animal_id', 'animal_id');
    }

    /**
     * Get the target display name (batch or individual animal).
     */
    public function getTargetDisplayAttribute()
    {
        if ($this->apply_to === 'batch' && $this->batch) {
            return 'Batch: ' . $this->batch->batch_id;
        } elseif ($this->apply_to === 'individual' && $this->individualAnimal) {
            return 'Individual: ' . $this->animal_id;
        }
        
        return $this->apply_to === 'batch' ? 'Batch: ' . $this->batch_id : 'Individual: ' . $this->animal_id;
    }

    /**
     * Get the total cost of this medicine application.
     */
    public function getTotalCostAttribute()
    {
        return $this->cost_per_unit ? $this->quantity * $this->cost_per_unit : 0;
    }

    /**
     * Get the formatted quantity with unit.
     */
    public function getQuantityWithUnitAttribute()
    {
        return number_format($this->quantity, 2) . ' ' . $this->unit;
    }

    /**
     * Scope to filter by application type.
     */
    public function scopeForBatches($query)
    {
        return $query->where('apply_to', 'batch');
    }

    /**
     * Scope to filter by individual animals.
     */
    public function scopeForIndividuals($query)
    {
        return $query->where('apply_to', 'individual');
    }

    /**
     * Scope to filter by date range.
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('medicine_date', [$startDate, $endDate]);
    }

    /**
     * Scope to get recent records.
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('medicine_date', '>=', Carbon::now()->subDays($days));
    }
}
