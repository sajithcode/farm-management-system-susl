<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'item',
        'source_type',
        'source_id',
        'quantity',
        'price',
        'buyer',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'date' => 'date',
        'quantity' => 'decimal:2',
        'price' => 'decimal:2',
    ];

    /**
     * Get the user who recorded the sale
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the source (batch or individual animal)
     */
    public function source(): MorphTo
    {
        return $this->morphTo('source', 'source_type', 'source_id');
    }

    /**
     * Get the source as batch
     */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class, 'source_id')
                    ->where('source_type', 'batch');
    }

    /**
     * Get the source as individual animal
     */
    public function individualAnimal(): BelongsTo
    {
        return $this->belongsTo(IndividualAnimal::class, 'source_id')
                    ->where('source_type', 'individual_animal');
    }

    /**
     * Get formatted source name
     */
    public function getSourceNameAttribute(): string
    {
        if ($this->source_type === 'batch') {
            $batch = Batch::find($this->source_id);
            return $batch ? "Batch #{$batch->batch_id}" : 'Unknown Batch';
        } elseif ($this->source_type === 'individual_animal') {
            $animal = IndividualAnimal::find($this->source_id);
            return $animal ? "Animal #{$animal->animal_id}" : 'Unknown Animal';
        }
        
        return 'Unknown Source';
    }
}
