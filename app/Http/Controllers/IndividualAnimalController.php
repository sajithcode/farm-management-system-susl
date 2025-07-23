<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IndividualAnimal;
use App\Models\IndividualAnimalFeed;
use App\Models\IndividualAnimalDeath;
use App\Models\IndividualAnimalSlaughter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class IndividualAnimalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 13. All Individual Animals Page
    public function index()
    {
        $animals = IndividualAnimal::with(['user', 'feedRecords', 'deathRecord', 'slaughterRecord'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('individual-animals.index', compact('animals'));
    }

    // 14. Add New Individual Animal - Show Form
    public function create()
    {
        return view('individual-animals.create');
    }

    // 14. Add New Individual Animal - Store
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'animal_id' => 'required|string|max:255|unique:individual_animals',
            'animal_type' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before_or_equal:today',
            'supplier' => 'nullable|string|max:255',
            'responsible_person' => 'required|string|max:255',
            'gender' => 'required|in:male,female,unknown',
            'current_weight' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        IndividualAnimal::create([
            'animal_id' => $request->animal_id,
            'animal_type' => $request->animal_type,
            'date_of_birth' => $request->date_of_birth,
            'supplier' => $request->supplier,
            'responsible_person' => $request->responsible_person,
            'gender' => $request->gender,
            'current_weight' => $request->current_weight,
            'notes' => $request->notes,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('individual-animals.index')
            ->with('success', 'Individual Animal "' . $request->animal_id . '" created successfully!');
    }

    // 15. Individual Animal Details Page
    public function show(IndividualAnimal $individualAnimal)
    {
        $individualAnimal->load(['feedRecords.user', 'deathRecord.user', 'slaughterRecord.user']);
        
        return view('individual-animals.show', compact('individualAnimal'));
    }

    // 16. Feed Individual Animal Page - Show Form
    public function showFeedForm(IndividualAnimal $individualAnimal)
    {
        // Check if animal is alive
        if ($individualAnimal->status !== 'alive') {
            return redirect()->route('individual-animals.show', $individualAnimal)
                ->with('error', 'Cannot feed an animal that is not alive.');
        }
        
        return view('individual-animals.feed', compact('individualAnimal'));
    }

    // 16. Feed Individual Animal - Store
    public function storeFeed(Request $request, IndividualAnimal $individualAnimal)
    {
        // Check if animal is alive
        if ($individualAnimal->status !== 'alive') {
            return redirect()->back()
                ->with('error', 'Cannot feed an animal that is not alive.');
        }

        $validator = Validator::make($request->all(), [
            'feed_date' => 'required|date|before_or_equal:today|after_or_equal:' . $individualAnimal->date_of_birth->format('Y-m-d'),
            'feed_type' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0.01',
            'unit' => 'required|string|max:20',
            'cost_per_unit' => 'nullable|numeric|min:0',
            'administered_by' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        IndividualAnimalFeed::create([
            'individual_animal_id' => $individualAnimal->id,
            'feed_date' => $request->feed_date,
            'feed_type' => $request->feed_type,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'cost_per_unit' => $request->cost_per_unit,
            'administered_by' => $request->administered_by,
            'notes' => $request->notes,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('individual-animals.show', $individualAnimal)
            ->with('success', 'Feed record added successfully!');
    }

    // 17. Record Individual Animal Death - Show Form
    public function showDeathForm(IndividualAnimal $individualAnimal)
    {
        // Check if animal is alive
        if ($individualAnimal->status !== 'alive') {
            return redirect()->route('individual-animals.show', $individualAnimal)
                ->with('error', 'Animal is already marked as ' . $individualAnimal->status . '.');
        }
        
        return view('individual-animals.death', compact('individualAnimal'));
    }

    // 17. Record Individual Animal Death - Store
    public function storeDeath(Request $request, IndividualAnimal $individualAnimal)
    {
        // Check if animal is alive
        if ($individualAnimal->status !== 'alive') {
            return redirect()->back()
                ->with('error', 'Animal is already marked as ' . $individualAnimal->status . '.');
        }

        $validator = Validator::make($request->all(), [
            'death_date' => 'required|date|before_or_equal:today|after_or_equal:' . $individualAnimal->date_of_birth->format('Y-m-d'),
            'weight' => 'nullable|numeric|min:0',
            'cause' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Calculate age at time of death
        $deathDate = \Carbon\Carbon::parse($request->death_date);
        $ageInDays = $individualAnimal->date_of_birth->diffInDays($deathDate);

        IndividualAnimalDeath::create([
            'individual_animal_id' => $individualAnimal->id,
            'death_date' => $request->death_date,
            'age_in_days' => $ageInDays,
            'weight' => $request->weight,
            'cause' => $request->cause,
            'notes' => $request->notes,
            'user_id' => Auth::id(),
        ]);

        // Update animal status
        $individualAnimal->markAsDead();

        return redirect()->route('individual-animals.show', $individualAnimal)
            ->with('success', 'Death record added successfully!');
    }

    // 18. Record Individual Slaughter - Show Form
    public function showSlaughterForm(IndividualAnimal $individualAnimal)
    {
        // Check if animal is alive
        if ($individualAnimal->status !== 'alive') {
            return redirect()->route('individual-animals.show', $individualAnimal)
                ->with('error', 'Animal is already marked as ' . $individualAnimal->status . '.');
        }
        
        return view('individual-animals.slaughter', compact('individualAnimal'));
    }

    // 18. Record Individual Slaughter - Store
    public function storeSlaughter(Request $request, IndividualAnimal $individualAnimal)
    {
        // Check if animal is alive
        if ($individualAnimal->status !== 'alive') {
            return redirect()->back()
                ->with('error', 'Animal is already marked as ' . $individualAnimal->status . '.');
        }

        $validator = Validator::make($request->all(), [
            'slaughter_date' => 'required|date|before_or_equal:today|after_or_equal:' . $individualAnimal->date_of_birth->format('Y-m-d'),
            'live_weight' => 'nullable|numeric|min:0',
            'dressed_weight' => 'nullable|numeric|min:0',
            'purpose' => 'nullable|string|max:255',
            'medicine_used' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Calculate age at time of slaughter
        $slaughterDate = \Carbon\Carbon::parse($request->slaughter_date);
        $ageInDays = $individualAnimal->date_of_birth->diffInDays($slaughterDate);

        IndividualAnimalSlaughter::create([
            'individual_animal_id' => $individualAnimal->id,
            'slaughter_date' => $request->slaughter_date,
            'age_in_days' => $ageInDays,
            'live_weight' => $request->live_weight,
            'dressed_weight' => $request->dressed_weight,
            'purpose' => $request->purpose,
            'medicine_used' => $request->medicine_used,
            'notes' => $request->notes,
            'user_id' => Auth::id(),
        ]);

        // Update animal status
        $individualAnimal->markAsSlaughtered();

        return redirect()->route('individual-animals.show', $individualAnimal)
            ->with('success', 'Slaughter record added successfully!');
    }
}
