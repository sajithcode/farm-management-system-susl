<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FeedType;
use App\Models\FeedIn;
use App\Models\FeedOut;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FeedManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Allow all authenticated users to access feed management for now
        // You can restrict to admin only by uncommenting the line below:
        // $this->middleware(function ($request, $next) {
        //     if (Auth::user()->role !== 'admin') {
        //         abort(403, 'Access denied. Only administrators can manage feed stock.');
        //     }
        //     return $next($request);
        // });
    }

    // Feed In (Stock Entry) Methods
    public function feedInIndex()
    {
        $feedIns = FeedIn::with(['feedType', 'user'])
            ->orderBy('date', 'desc')
            ->paginate(15);
        
        $feedTypes = FeedType::where('is_active', true)->get();
        
        return view('feed.feed-in.index', compact('feedIns', 'feedTypes'));
    }

    public function feedInStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'feed_type_id' => 'required|exists:feed_types,id',
            'quantity' => 'required|numeric|min:0.01',
            'supplier' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        FeedIn::create([
            'date' => $request->date,
            'feed_type_id' => $request->feed_type_id,
            'quantity' => $request->quantity,
            'supplier' => $request->supplier,
            'notes' => $request->notes,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('feed.in.index')
            ->with('success', 'Feed stock entry recorded successfully!');
    }

    // Feed Out (Stock Issue) Methods
    public function feedOutIndex()
    {
        $feedOuts = FeedOut::with(['feedType', 'user'])
            ->orderBy('date', 'desc')
            ->paginate(15);
        
        $feedTypes = FeedType::where('is_active', true)->get();
        
        return view('feed.feed-out.index', compact('feedOuts', 'feedTypes'));
    }

    public function feedOutStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'feed_type_id' => 'required|exists:feed_types,id',
            'quantity' => 'required|numeric|min:0.01',
            'issued_to' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if there's enough stock
        $feedType = FeedType::find($request->feed_type_id);
        $currentStock = $feedType->current_stock;
        
        if ($currentStock < $request->quantity) {
            return redirect()->back()
                ->withErrors(['quantity' => 'Insufficient stock. Available: ' . $currentStock . ' ' . $feedType->unit])
                ->withInput();
        }

        FeedOut::create([
            'date' => $request->date,
            'feed_type_id' => $request->feed_type_id,
            'quantity' => $request->quantity,
            'issued_to' => $request->issued_to,
            'location' => $request->location,
            'notes' => $request->notes,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('feed.out.index')
            ->with('success', 'Feed issued successfully!');
    }

    // Feed Stock Overview Methods
    public function stockOverview(Request $request)
    {
        $query = FeedType::where('is_active', true);
        
        // Get filter parameters
        $feedTypeFilter = $request->get('feed_type');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        
        $feedTypes = $query->get();
        
        // Calculate stock data for each feed type
        $stockData = [];
        foreach ($feedTypes as $feedType) {
            $feedInQuery = $feedType->feedIns();
            $feedOutQuery = $feedType->feedOuts();
            
            // Apply date filters if provided
            if ($dateFrom) {
                $feedInQuery->where('date', '>=', $dateFrom);
                $feedOutQuery->where('date', '>=', $dateFrom);
            }
            
            if ($dateTo) {
                $feedInQuery->where('date', '<=', $dateTo);
                $feedOutQuery->where('date', '<=', $dateTo);
            }
            
            $totalIn = $feedInQuery->sum('quantity');
            $totalOut = $feedOutQuery->sum('quantity');
            $currentStock = $totalIn - $totalOut;
            
            $stockData[] = [
                'feed_type' => $feedType,
                'total_in' => $totalIn,
                'total_out' => $totalOut,
                'current_stock' => $currentStock,
            ];
        }
        
        $allFeedTypes = FeedType::where('is_active', true)->get();
        
        return view('feed.stock-overview.index', compact('stockData', 'allFeedTypes', 'feedTypeFilter', 'dateFrom', 'dateTo'));
    }

    // Feed Type Management (Admin only)
    public function feedTypeIndex()
    {
        // Only allow admin users to manage feed types
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Only administrators can manage feed types.');
        }
        
        $feedTypes = FeedType::paginate(15);
        return view('feed.feed-types.index', compact('feedTypes'));
    }

    public function feedTypeStore(Request $request)
    {
        // Only allow admin users to create feed types
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Only administrators can manage feed types.');
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:feed_types',
            'description' => 'nullable|string|max:1000',
            'unit' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        FeedType::create($request->only(['name', 'description', 'unit']));

        return redirect()->route('feed.types.index')
            ->with('success', 'Feed type created successfully!');
    }
}
