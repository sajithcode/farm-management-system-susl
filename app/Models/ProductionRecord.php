<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ProductionRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_date',
        'batch_id',
        'product_type',
        'quantity',
        'unit',
        'quality_grade',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'production_date' => 'date',
        'quantity' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id', 'batch_id');
    }

    // Accessors
    public function getFormattedDateAttribute()
    {
        return $this->production_date->format('M d, Y');
    }

    public function getQuantityWithUnitAttribute()
    {
        return number_format($this->quantity, 2) . ' ' . $this->unit;
    }

    public function getProductTypeDisplayAttribute()
    {
        switch($this->product_type) {
            case 'eggs':
                return '🥚 Eggs';
            case 'meat':
                return '🥩 Meat';
            case 'milk':
                return '🥛 Milk';
            case 'other':
                return '📦 Other';
            default:
                return ucfirst($this->product_type);
        }
    }

    // Scopes
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('production_date', '>=', Carbon::now()->subDays($days));
    }

    public function scopeForBatch($query, $batchId)
    {
        return $query->where('batch_id', $batchId);
    }

    public function scopeForProductType($query, $productType)
    {
        return $query->where('product_type', $productType);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('production_date', Carbon::now()->month)
                    ->whereYear('production_date', Carbon::now()->year);
    }
}
