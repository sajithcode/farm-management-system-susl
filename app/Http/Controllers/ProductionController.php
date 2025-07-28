<?php

namespace App\Http\Controllers;

use App\Models\ProductionRecord;
use App\Models\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductionController extends Controller
{
    /**
     * Display a listing of all production records.
     */
    public function index(Request $request)
    {
        $query = ProductionRecord::with(['user', 'batch'])
                     ->orderBy('production_date', 'desc')
                     ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('date_from')) {
            $query->where('production_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('production_date', '<=', $request->date_to);
        }

        if ($request->filled('product_type')) {
            $query->where('product_type', $request->product_type);
        }

        if ($request->filled('batch')) {
            $query->where('batch_id', $request->batch);
        }

        $productions = $query->paginate(15);

        // Get batches for filter dropdown
        $batches = Batch::orderBy('batch_id')->get(['batch_id', 'animal_type', 'current_count']);

        // Calculate statistics
        $statistics = [
            'total_records' => ProductionRecord::count(),
            'this_month' => ProductionRecord::whereMonth('production_date', now()->month)
                                          ->whereYear('production_date', now()->year)
                                          ->count(),
            'this_week' => ProductionRecord::whereBetween('production_date', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
            'today' => ProductionRecord::whereDate('production_date', now()->toDateString())->count(),
        ];

        return view('production.index', compact('productions', 'batches', 'statistics'));
    }

    /**
     * Show the form for creating a new production record.
     */
    public function create()
    {
        $batches = Batch::orderBy('batch_id')->get(['batch_id', 'animal_type', 'current_count']);
        
        return view('production.create', compact('batches'));
    }

    /**
     * Store a newly created production record.
     */
    public function store(Request $request)
    {
        $rules = [
            'production_date' => 'required|date|before_or_equal:today',
            'batch_id' => 'required|string|exists:batches,batch_id',
            'product_type' => 'required|in:eggs,meat,milk,other',
            'quantity' => 'required|numeric|min:0.01',
            'unit' => 'required|string|max:50',
            'notes' => 'nullable|string|max:1000',
        ];

        $validatedData = $request->validate($rules);

        // Create production record
        ProductionRecord::create([
            'production_date' => $validatedData['production_date'],
            'batch_id' => $validatedData['batch_id'],
            'product_type' => $validatedData['product_type'],
            'quantity' => $validatedData['quantity'],
            'unit' => $validatedData['unit'],
            'notes' => $validatedData['notes'],
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('production.index')
                        ->with('success', 'Production record added successfully!');
    }

    /**
     * Display the specified production record.
     */
    public function show(ProductionRecord $production)
    {
        $production->load(['user', 'batch']);
        
        // Calculate related statistics
        $batchProductionCount = ProductionRecord::where('batch_id', $production->batch_id)->count();
        $productTypeMonthCount = ProductionRecord::where('product_type', $production->product_type)
                                               ->whereMonth('production_date', now()->month)
                                               ->whereYear('production_date', now()->year)
                                               ->count();
        $userTotalCount = ProductionRecord::where('user_id', $production->user_id)->count();
        
        return view('production.show', compact(
            'production', 
            'batchProductionCount', 
            'productTypeMonthCount', 
            'userTotalCount'
        ));
    }

    /**
     * Show the form for editing the specified production record.
     */
    public function edit(ProductionRecord $production)
    {
        $batches = Batch::orderBy('batch_id')->get(['batch_id', 'animal_type', 'current_count']);
        
        return view('production.edit', compact('production', 'batches'));
    }

    /**
     * Update the specified production record.
     */
    public function update(Request $request, ProductionRecord $production)
    {
        $rules = [
            'production_date' => 'required|date|before_or_equal:today',
            'batch_id' => 'required|string|exists:batches,batch_id',
            'product_type' => 'required|in:eggs,meat,milk,other',
            'quantity' => 'required|numeric|min:0.01',
            'unit' => 'required|string|max:50',
            'notes' => 'nullable|string|max:1000',
        ];

        $validatedData = $request->validate($rules);

        // Update production record
        $production->update([
            'production_date' => $validatedData['production_date'],
            'batch_id' => $validatedData['batch_id'],
            'product_type' => $validatedData['product_type'],
            'quantity' => $validatedData['quantity'],
            'unit' => $validatedData['unit'],
            'notes' => $validatedData['notes'],
        ]);

        return redirect()->route('production.index')
                        ->with('success', 'Production record updated successfully!');
    }

    /**
     * Remove the specified production record.
     */
    public function destroy(ProductionRecord $production)
    {
        $production->delete();

        return redirect()->route('production.index')
                        ->with('success', 'Production record deleted successfully!');
    }
}

