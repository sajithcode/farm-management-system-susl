<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Batch;
use App\Models\BatchFeed;
use App\Models\BatchDeath;
use App\Models\BatchSlaughter;
use App\Models\FeedType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BatchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 7. All Batches Page
    public function index()
    {
        $batches = Batch::with(['user', 'feedRecords', 'deathRecords', 'slaughterRecords'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('batches.index', compact('batches'));
    }

    // 8. Add New Batch - Show Form
    public function create()
    {
        return view('batches.create');
    }

    // 8. Add New Batch - Store
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'batch_id' => 'required|string|max:255|unique:batches',
            'name' => 'nullable|string|max:255',
            'animal_type' => 'required|string|max:255',
            'start_date' => 'required|date|before_or_equal:today',
            'initial_count' => 'required|integer|min:1',
            'supplier' => 'nullable|string|max:255',
            'responsible_person' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Batch::create([
            'batch_id' => $request->batch_id,
            'name' => $request->name,
            'animal_type' => $request->animal_type,
            'start_date' => $request->start_date,
            'initial_count' => $request->initial_count,
            'current_count' => $request->initial_count, // Initially same as initial count
            'supplier' => $request->supplier,
            'responsible_person' => $request->responsible_person,
            'notes' => $request->notes,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('batches.index')
            ->with('success', 'Batch "' . $request->batch_id . '" created successfully!');
    }

    // 9. Batch Details Page
    public function show(Batch $batch)
    {
        $batch->load(['feedRecords.user', 'deathRecords.user', 'slaughterRecords.user']);
        
        return view('batches.show', compact('batch'));
    }

    // 10. Feed Batch Page - Show Form
    public function showFeedForm(Batch $batch)
    {
        return view('batches.feed', compact('batch'));
    }

    // 10. Feed Batch - Store
    public function storeFeed(Request $request, Batch $batch)
    {
        $validator = Validator::make($request->all(), [
            'feed_date' => 'required|date|before_or_equal:today|after_or_equal:' . $batch->start_date->format('Y-m-d'),
            'feed_type' => 'required|string|max:255',
            'custom_feed_type' => 'nullable|string|max:255',
            'quantity' => 'required|numeric|min:0.01',
            'unit' => 'required|string|max:20',
            'cost_per_unit' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Use custom feed type if "Other" is selected
        $feedType = $request->feed_type === 'Other' ? $request->custom_feed_type : $request->feed_type;

        // Calculate batch age on the feeding date
        $feedDate = \Carbon\Carbon::parse($request->feed_date);
        $batchAgeInDays = $batch->start_date->diffInDays($feedDate);

        BatchFeed::create([
            'batch_id' => $batch->id,
            'feed_date' => $request->feed_date,
            'feed_type' => $feedType,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'cost_per_unit' => $request->cost_per_unit,
            'batch_age_days' => $batchAgeInDays,
            'notes' => $request->notes,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('batches.show', $batch)
            ->with('success', 'Feed record added successfully!');
    }

    // 11. Record Batch Death - Show Form
    public function showDeathForm(Batch $batch)
    {
        return view('batches.death', compact('batch'));
    }

    // 11. Record Batch Death - Store
    public function storeDeath(Request $request, Batch $batch)
    {
        $validator = Validator::make($request->all(), [
            'death_date' => 'required|date|before_or_equal:today|after_or_equal:' . $batch->start_date->format('Y-m-d'),
            'count' => 'required|integer|min:1|max:' . $batch->current_count,
            'cause' => 'nullable|string|max:255',
            'custom_cause' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Use custom cause if "Other" is selected
        $cause = $request->cause === 'Other' ? $request->custom_cause : $request->cause;

        // Calculate batch age on the death date
        $deathDate = \Carbon\Carbon::parse($request->death_date);
        $batchAgeInDays = $batch->start_date->diffInDays($deathDate);

        BatchDeath::create([
            'batch_id' => $batch->id,
            'death_date' => $request->death_date,
            'count' => $request->count,
            'batch_age_days' => $batchAgeInDays,
            'cause' => $cause,
            'notes' => $request->notes,
            'user_id' => Auth::id(),
        ]);

        // Update batch current count
        $batch->updateCurrentCount();

        return redirect()->route('batches.show', $batch)
            ->with('success', 'Death record added successfully!');
    }

    // 12. Record Batch Slaughter - Show Form
    public function showSlaughterForm(Batch $batch)
    {
        return view('batches.slaughter', compact('batch'));
    }

        // 13. Record Batch Slaughter - Store
    public function storeSlaughter(Request $request, Batch $batch)
    {
        $validator = Validator::make($request->all(), [
            'slaughter_date' => 'required|date|before_or_equal:today|after_or_equal:' . $batch->start_date->format('Y-m-d'),
            'count' => 'required|integer|min:1|max:' . $batch->current_count,
            'total_weight' => 'nullable|numeric|min:0',
            'live_weight' => 'nullable|numeric|min:0',
            'dressed_weight' => 'nullable|numeric|min:0',
            'purpose' => 'nullable|string|max:255',
            'custom_purpose' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Use custom purpose if "Other" is selected
        $purpose = $request->purpose === 'Other' ? $request->custom_purpose : $request->purpose;

        // Calculate batch age on the slaughter date
        $slaughterDate = \Carbon\Carbon::parse($request->slaughter_date);
        $batchAgeInDays = $batch->start_date->diffInDays($slaughterDate);

        BatchSlaughter::create([
            'batch_id' => $batch->id,
            'slaughter_date' => $request->slaughter_date,
            'count' => $request->count,
            'batch_age_days' => $batchAgeInDays,
            'total_weight' => $request->total_weight,
            'live_weight' => $request->live_weight,
            'dressed_weight' => $request->dressed_weight,
            'purpose' => $purpose,
            'notes' => $request->notes,
            'user_id' => Auth::id(),
        ]);

        // Update batch current count
        $batch->updateCurrentCount();

        return redirect()->route('batches.show', $batch)
            ->with('success', 'Slaughter record added successfully!');
    }
}
