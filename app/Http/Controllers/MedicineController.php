<?php

namespace App\Http\Controllers;

use App\Models\MedicineRecord;
use App\Models\Batch;
use App\Models\IndividualAnimal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicineController extends Controller
{
    /**
     * Display a listing of all medicine records.
     */
    public function index(Request $request)
    {
        $query = MedicineRecord::with(['user', 'batch', 'individualAnimal'])
                    ->orderBy('medicine_date', 'desc')
                    ->orderBy('created_at', 'desc');

        // Filter by application type
        if ($request->filled('apply_to')) {
            $query->where('apply_to', $request->apply_to);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->where('medicine_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('medicine_date', '<=', $request->end_date);
        }

        // Search by medicine name or target
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('medicine_name', 'like', "%{$search}%")
                  ->orWhere('animal_id', 'like', "%{$search}%")
                  ->orWhere('administered_by', 'like', "%{$search}%");
            });
        }

        $medicines = $query->paginate(15);

        // Statistics
        $totalRecords = MedicineRecord::count();
        $recentRecords = MedicineRecord::recent(30)->count();
        $batchRecords = MedicineRecord::forBatches()->count();
        $individualRecords = MedicineRecord::forIndividuals()->count();

        return view('medicines.index', compact('medicines', 'totalRecords', 'recentRecords', 'batchRecords', 'individualRecords'));
    }

    /**
     * Show the form for creating a new medicine record.
     */
    public function create()
    {
        // Get all batches (since there's no status column, we get all batches)
        // You can add additional filtering logic here if needed
        $batches = Batch::orderBy('batch_id')
                       ->get();
        
        $individualAnimals = IndividualAnimal::where('status', 'alive')
                                           ->orderBy('animal_id')
                                           ->get();

        return view('medicines.create', compact('batches', 'individualAnimals'));
    }

    /**
     * Store a newly created medicine record in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'medicine_date' => 'required|date|before_or_equal:today',
            'apply_to' => 'required|in:batch,individual',
            'target_id' => 'required|string',
            'medicine_name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0.01',
            'unit' => 'required|string|max:50',
            'cost_per_unit' => 'nullable|numeric|min:0',
            'administered_by' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Validate target existence
        if ($validated['apply_to'] === 'batch') {
            $batch = Batch::where('batch_id', $validated['target_id'])->first();
            if (!$batch) {
                return back()->withErrors(['target_id' => 'The selected batch does not exist.']);
            }
            // Note: Removed status check since batches table doesn't have status column
        } else {
            $individual = IndividualAnimal::where('animal_id', $validated['target_id'])->first();
            if (!$individual) {
                return back()->withErrors(['target_id' => 'The selected individual animal does not exist.']);
            }
            if ($individual->status !== 'alive') {
                return back()->withErrors(['target_id' => 'Medicine can only be applied to alive animals.']);
            }
        }

        // Create medicine record
        $medicineData = [
            'medicine_date' => $validated['medicine_date'],
            'apply_to' => $validated['apply_to'],
            'medicine_name' => $validated['medicine_name'],
            'quantity' => $validated['quantity'],
            'unit' => $validated['unit'],
            'cost_per_unit' => $validated['cost_per_unit'],
            'administered_by' => $validated['administered_by'],
            'notes' => $validated['notes'],
            'user_id' => Auth::id(),
        ];

        if ($validated['apply_to'] === 'batch') {
            $medicineData['batch_id'] = $batch->id;
        } else {
            $medicineData['animal_id'] = $validated['target_id'];
        }

        MedicineRecord::create($medicineData);

        return redirect()->route('medicines.index')
                        ->with('success', 'Medicine record added successfully!');
    }

    /**
     * Display the specified medicine record.
     */
    public function show(MedicineRecord $medicine)
    {
        $medicine->load(['user', 'batch', 'individualAnimal']);
        
        return view('medicines.show', compact('medicine'));
    }

    /**
     * Show the form for editing the specified medicine record.
     */
    public function edit(MedicineRecord $medicine)
    {
        // Get all batches and individual animals
        $batches = Batch::orderBy('batch_id')
                       ->get();
        
        $individualAnimals = IndividualAnimal::where('status', 'alive')
                                           ->orderBy('animal_id')
                                           ->get();

        return view('medicines.edit', compact('medicine', 'batches', 'individualAnimals'));
    }

    /**
     * Update the specified medicine record in storage.
     */
    public function update(Request $request, MedicineRecord $medicine)
    {
        $validated = $request->validate([
            'medicine_date' => 'required|date|before_or_equal:today',
            'apply_to' => 'required|in:batch,individual',
            'target_id' => 'required|string',
            'medicine_name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0.01',
            'unit' => 'required|string|max:50',
            'cost_per_unit' => 'nullable|numeric|min:0',
            'administered_by' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Validate target existence
        if ($validated['apply_to'] === 'batch') {
            $batch = Batch::where('batch_id', $validated['target_id'])->first();
            if (!$batch) {
                return back()->withErrors(['target_id' => 'The selected batch does not exist.']);
            }
        } else {
            $individual = IndividualAnimal::where('animal_id', $validated['target_id'])->first();
            if (!$individual) {
                return back()->withErrors(['target_id' => 'The selected individual animal does not exist.']);
            }
        }

        // Update medicine record
        $medicineData = [
            'medicine_date' => $validated['medicine_date'],
            'apply_to' => $validated['apply_to'],
            'medicine_name' => $validated['medicine_name'],
            'quantity' => $validated['quantity'],
            'unit' => $validated['unit'],
            'cost_per_unit' => $validated['cost_per_unit'],
            'administered_by' => $validated['administered_by'],
            'notes' => $validated['notes'],
        ];

        if ($validated['apply_to'] === 'batch') {
            $medicineData['batch_id'] = $batch->id;
            $medicineData['animal_id'] = null;
        } else {
            $medicineData['animal_id'] = $validated['target_id'];
            $medicineData['batch_id'] = null;
        }

        $medicine->update($medicineData);

        return redirect()->route('medicines.index')
                        ->with('success', 'Medicine record updated successfully!');
    }

    /**
     * Remove the specified medicine record from storage.
     */
    public function destroy(MedicineRecord $medicine)
    {
        $medicine->delete();

        return redirect()->route('medicines.index')
                        ->with('success', 'Medicine record deleted successfully!');
    }

    /**
     * Get target suggestions via AJAX.
     */
    public function getTargets(Request $request)
    {
        $type = $request->get('type');
        $search = $request->get('search', '');

        if ($type === 'batch') {
            $targets = Batch::where('batch_id', 'like', "%{$search}%")
                           ->orderBy('batch_id')
                           ->limit(10)
                           ->get(['id', 'batch_id', 'animal_type', 'initial_count'])
                           ->map(function($batch) {
                               return [
                                   'id' => $batch->batch_id,
                                   'text' => $batch->batch_id . ' (' . ucfirst($batch->animal_type) . ' - ' . $batch->initial_count . ' animals)',
                               ];
                           });
        } else {
            $targets = IndividualAnimal::where('status', 'alive')
                                     ->where('animal_id', 'like', "%{$search}%")
                                     ->orderBy('animal_id')
                                     ->limit(10)
                                     ->get(['animal_id', 'animal_type', 'gender'])
                                     ->map(function($animal) {
                                         return [
                                             'id' => $animal->animal_id,
                                             'text' => $animal->animal_id . ' (' . ucfirst($animal->animal_type) . ' - ' . ucfirst($animal->gender) . ')',
                                         ];
                                     });
        }

        return response()->json($targets);
    }
}
