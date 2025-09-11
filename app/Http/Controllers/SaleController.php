<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Batch;
use App\Models\IndividualAnimal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    /**
     * Display a listing of the sales.
     */
    public function index()
    {
        $sales = Sale::with(['user'])
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('sales.index', compact('sales'));
    }

    /**
     * Show the form for creating a new sale.
     */
    public function create()
    {
        $batches = Batch::where('current_count', '>', 0)
            ->orderBy('batch_id')
            ->get();

        $individualAnimals = IndividualAnimal::where('status', 'alive')
            ->orderBy('animal_id')
            ->get();

        return view('sales.create', compact('batches', 'individualAnimals'));
    }

    /**
     * Store a newly created sale in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required|date|before_or_equal:today',
            'item' => 'required|in:eggs,meat',
            'source_type' => 'required|in:batch,individual_animal',
            'source_id' => 'required|integer',
            'quantity' => 'required|numeric|min:0.01',
            'price' => 'required|numeric|min:0.01',
            'buyer' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Validate that the source exists
        if ($validatedData['source_type'] === 'batch') {
            $source = Batch::find($validatedData['source_id']);
            if (!$source) {
                return back()->withErrors(['source_id' => 'Selected batch not found.'])->withInput();
            }
        } elseif ($validatedData['source_type'] === 'individual_animal') {
            $source = IndividualAnimal::find($validatedData['source_id']);
            if (!$source || $source->status !== 'alive') {
                return back()->withErrors(['source_id' => 'Selected animal not found or not alive.'])->withInput();
            }
        }

        $validatedData['user_id'] = Auth::id();

        Sale::create($validatedData);

        return redirect()->route('sales.index')->with('success', 'Sale record created successfully!');
    }

    /**
     * Display the specified sale.
     */
    public function show(Sale $sale)
    {
        return view('sales.show', compact('sale'));
    }

    /**
     * Show the form for editing the specified sale.
     */
    public function edit(Sale $sale)
    {
        $batches = Batch::where('current_count', '>', 0)
            ->orderBy('batch_id')
            ->get();

        $individualAnimals = IndividualAnimal::where('status', 'alive')
            ->orderBy('animal_id')
            ->get();

        return view('sales.edit', compact('sale', 'batches', 'individualAnimals'));
    }

    /**
     * Update the specified sale in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        $validatedData = $request->validate([
            'date' => 'required|date|before_or_equal:today',
            'item' => 'required|in:eggs,meat',
            'source_type' => 'required|in:batch,individual_animal',
            'source_id' => 'required|integer',
            'quantity' => 'required|numeric|min:0.01',
            'price' => 'required|numeric|min:0.01',
            'buyer' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Validate that the source exists
        if ($validatedData['source_type'] === 'batch') {
            $source = Batch::find($validatedData['source_id']);
            if (!$source) {
                return back()->withErrors(['source_id' => 'Selected batch not found.'])->withInput();
            }
        } elseif ($validatedData['source_type'] === 'individual_animal') {
            $source = IndividualAnimal::find($validatedData['source_id']);
            if (!$source || $source->status !== 'alive') {
                return back()->withErrors(['source_id' => 'Selected animal not found or not alive.'])->withInput();
            }
        }

        $sale->update($validatedData);

        return redirect()->route('sales.index')->with('success', 'Sale record updated successfully!');
    }

    /**
     * Remove the specified sale from storage.
     */
    public function destroy(Sale $sale)
    {
        $sale->delete();

        return redirect()->route('sales.index')->with('success', 'Sale record deleted successfully!');
    }

    /**
     * Get sources for AJAX requests
     */
    public function getSources(Request $request)
    {
        $sourceType = $request->get('source_type');
        
        if ($sourceType === 'batch') {
            $sources = Batch::where('current_count', '>', 0)
                ->orderBy('batch_id')
                ->get()
                ->map(function ($batch) {
                    return [
                        'id' => $batch->id,
                        'text' => "Batch #{$batch->batch_id} ({$batch->animal_type})"
                    ];
                });
        } elseif ($sourceType === 'individual_animal') {
            $sources = IndividualAnimal::where('status', 'alive')
                ->orderBy('animal_id')
                ->get()
                ->map(function ($animal) {
                    return [
                        'id' => $animal->id,
                        'text' => "Animal #{$animal->animal_id} ({$animal->animal_type})"
                    ];
                });
        } else {
            $sources = collect();
        }

        return response()->json($sources);
    }
}
