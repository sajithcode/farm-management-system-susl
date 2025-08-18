<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Batch;
use App\Models\IndividualAnimal;
use App\Models\BatchDeath;
use App\Models\BatchSlaughter;
use App\Models\IndividualAnimalDeath;
use App\Models\IndividualAnimalSlaughter;
use App\Models\BatchFeed;
use App\Models\IndividualAnimalFeed;
use App\Models\ProductionRecord;  
use App\Models\MedicineRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Display the reports dashboard
     */
    public function dashboard()
    {
        // Get date range from request or default to last 30 days
        $startDate = request('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = request('end_date', Carbon::now()->format('Y-m-d'));

        // Sales statistics
        $salesData = $this->getSalesData($startDate, $endDate);
        
        // Feed statistics
        $feedData = $this->getFeedData($startDate, $endDate);
        
        // Death statistics
        $deathData = $this->getDeathData($startDate, $endDate);
        
        // Slaughter statistics
        $slaughterData = $this->getSlaughterData($startDate, $endDate);
        
        // Production statistics
        $productionData = $this->getProductionData($startDate, $endDate);

        return view('reports.dashboard', compact(
            'salesData', 'feedData', 'deathData', 'slaughterData', 'productionData',
            'startDate', 'endDate'
        ));
    }

    /**
     * Show the generate report form
     */
    public function generate()
    {
        return view('reports.generate');
    }

    /**
     * Generate and preview report
     */
    public function preview(Request $request)
    {
        $validatedData = $request->validate([
            'report_type' => 'required|in:sales,feed,death,slaughter,production,medicine',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
        ]);

        $reportData = $this->getReportData(
            $validatedData['report_type'],
            $validatedData['start_date'],
            $validatedData['end_date'],
            $validatedData['location'] ?? null
        );

        return view('reports.preview', compact('reportData', 'validatedData'));
    }

    /**
     * Export report as PDF
     */
    public function exportPdf(Request $request)
    {
        $validatedData = $request->validate([
            'report_type' => 'required|in:sales,feed,death,slaughter,production,medicine',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
        ]);

        $reportData = $this->getReportData(
            $validatedData['report_type'],
            $validatedData['start_date'],
            $validatedData['end_date'],
            $validatedData['location'] ?? null
        );

        $title = ucfirst($validatedData['report_type']) . ' Report';
        $reportType = $validatedData['report_type'];
        $dateFrom = $validatedData['start_date'];
        $dateTo = $validatedData['end_date'];
        $animalType = 'all';
        $data = $reportData;

        $filename = $validatedData['report_type'] . '_report_' . 
                   $validatedData['start_date'] . '_to_' . 
                   $validatedData['end_date'] . '.pdf';

        // Generate PDF using DomPDF
        $pdf = Pdf::loadView('reports.pdf', compact(
            'title', 'reportType', 'dateFrom', 'dateTo', 'animalType', 'data'
        ));

        return $pdf->download($filename);
    }

    /**
     * Export report as Word document
     */
    public function exportWord(Request $request)
    {
        $validatedData = $request->validate([
            'report_type' => 'required|in:sales,feed,death,slaughter,production,medicine',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
        ]);

        $reportData = $this->getReportData(
            $validatedData['report_type'],
            $validatedData['start_date'],
            $validatedData['end_date'],
            $validatedData['location'] ?? null
        );

        $title = ucfirst($validatedData['report_type']) . ' Report';
        $reportType = $validatedData['report_type'];
        $dateFrom = $validatedData['start_date'];
        $dateTo = $validatedData['end_date'];
        $animalType = 'all';
        $data = $reportData;

        $filename = $validatedData['report_type'] . '_report_' . 
                   $validatedData['start_date'] . '_to_' . 
                   $validatedData['end_date'] . '.docx';

        return response()->view('reports.word', compact(
            'title', 'reportType', 'dateFrom', 'dateTo', 'animalType', 'data'
        ))->header('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
          ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Get sales data for dashboard
     */
    private function getSalesData($startDate, $endDate)
    {
        $sales = Sale::whereBetween('date', [$startDate, $endDate])->get();
        
        return [
            'total_sales' => $sales->count(),
            'total_revenue' => $sales->sum('price'),
            'eggs_sales' => $sales->where('item', 'eggs')->count(),
            'meat_sales' => $sales->where('item', 'meat')->count(),
            'average_sale_value' => $sales->count() > 0 ? $sales->avg('price') : 0,
        ];
    }

    /**
     * Get feed data for dashboard
     */
    private function getFeedData($startDate, $endDate)
    {
        $batchFeeds = BatchFeed::whereBetween('feed_date', [$startDate, $endDate])->get();
        $individualFeeds = IndividualAnimalFeed::whereBetween('feed_date', [$startDate, $endDate])->get();
        
        return [
            'total_feed_records' => $batchFeeds->count() + $individualFeeds->count(),
            'total_feed_quantity' => $batchFeeds->sum('quantity') + $individualFeeds->sum('quantity'),
            'batch_feed_records' => $batchFeeds->count(),
            'individual_feed_records' => $individualFeeds->count(),
        ];
    }

    /**
     * Get death data for dashboard
     */
    private function getDeathData($startDate, $endDate)
    {
        $batchDeaths = BatchDeath::whereBetween('death_date', [$startDate, $endDate])->get();
        $individualDeaths = IndividualAnimalDeath::whereBetween('death_date', [$startDate, $endDate])->get();
        
        return [
            'total_deaths' => $batchDeaths->sum('count') + $individualDeaths->count(),
            'batch_deaths' => $batchDeaths->sum('count'),
            'individual_deaths' => $individualDeaths->count(),
            'death_records' => $batchDeaths->count() + $individualDeaths->count(),
        ];
    }

    /**
     * Get slaughter data for dashboard
     */
    private function getSlaughterData($startDate, $endDate)
    {
        $batchSlaughters = BatchSlaughter::whereBetween('slaughter_date', [$startDate, $endDate])->get();
        $individualSlaughters = IndividualAnimalSlaughter::whereBetween('slaughter_date', [$startDate, $endDate])->get();
        
        return [
            'total_slaughters' => $batchSlaughters->sum('count') + $individualSlaughters->count(),
            'batch_slaughters' => $batchSlaughters->sum('count'),
            'individual_slaughters' => $individualSlaughters->count(),
            'total_weight' => $batchSlaughters->sum('total_weight') + $individualSlaughters->sum('weight'),
        ];
    }

    /**
     * Get production data for dashboard
     */
    private function getProductionData($startDate, $endDate)
    {
        $productions = ProductionRecord::whereBetween('production_date', [$startDate, $endDate])->get();
        
        return [
            'total_records' => $productions->count(),
            'total_quantity' => $productions->sum('quantity'),
            'average_daily' => $productions->count() > 0 ? $productions->avg('quantity') : 0,
        ];
    }

    /**
     * Get report data based on type
     */
    private function getReportData($type, $startDate, $endDate, $location = null)
    {
        switch ($type) {
            case 'sales':
                return $this->getSalesReportData($startDate, $endDate, $location);
            case 'feed':
                return $this->getFeedReportData($startDate, $endDate, $location);
            case 'death':
                return $this->getDeathReportData($startDate, $endDate, $location);
            case 'slaughter':
                return $this->getSlaughterReportData($startDate, $endDate, $location);
            case 'production':
                return $this->getProductionReportData($startDate, $endDate, $location);
            case 'medicine':
                return $this->getMedicineReportData($startDate, $endDate, $location);
            default:
                return [];
        }
    }

    private function getSalesReportData($startDate, $endDate, $location)
    {
        $query = Sale::with(['user'])
            ->whereBetween('date', [$startDate, $endDate]);

        return [
            'title' => 'Sales Report',
            'data' => $query->orderBy('date', 'desc')->get(),
            'summary' => [
                'total_records' => $query->count(),
                'total_revenue' => $query->sum('price'),
                'total_quantity' => $query->sum('quantity'),
            ]
        ];
    }

    private function getFeedReportData($startDate, $endDate, $location)
    {
        $batchFeeds = BatchFeed::with(['batch', 'feedType', 'user'])
            ->whereBetween('feed_date', [$startDate, $endDate])
            ->get();

        $individualFeeds = IndividualAnimalFeed::with(['individualAnimal', 'feedType', 'user'])
            ->whereBetween('feed_date', [$startDate, $endDate])
            ->get();

        return [
            'title' => 'Feed Report',
            'batch_feeds' => $batchFeeds,
            'individual_feeds' => $individualFeeds,
            'summary' => [
                'total_records' => $batchFeeds->count() + $individualFeeds->count(),
                'total_quantity' => $batchFeeds->sum('quantity') + $individualFeeds->sum('quantity'),
            ]
        ];
    }

    private function getDeathReportData($startDate, $endDate, $location)
    {
        $batchDeaths = BatchDeath::with(['batch', 'user'])
            ->whereBetween('death_date', [$startDate, $endDate])
            ->get();

        $individualDeaths = IndividualAnimalDeath::with(['individualAnimal', 'user'])
            ->whereBetween('death_date', [$startDate, $endDate])
            ->get();

        return [
            'title' => 'Death Report',
            'batch_deaths' => $batchDeaths,
            'individual_deaths' => $individualDeaths,
            'summary' => [
                'total_deaths' => $batchDeaths->sum('count') + $individualDeaths->count(),
                'batch_deaths' => $batchDeaths->sum('count'),
                'individual_deaths' => $individualDeaths->count(),
            ]
        ];
    }

    private function getSlaughterReportData($startDate, $endDate, $location)
    {
        $batchSlaughters = BatchSlaughter::with(['batch', 'user'])
            ->whereBetween('slaughter_date', [$startDate, $endDate])
            ->get();

        $individualSlaughters = IndividualAnimalSlaughter::with(['individualAnimal', 'user'])
            ->whereBetween('slaughter_date', [$startDate, $endDate])
            ->get();

        return [
            'title' => 'Slaughter Report',
            'batch_slaughter' => $batchSlaughters,
            'individual_slaughter' => $individualSlaughters,
            'summary' => [
                'total_slaughtered' => $batchSlaughters->sum('count') + $individualSlaughters->count(),
                'total_weight' => $batchSlaughters->sum('total_weight') + $individualSlaughters->sum('weight'),
                'batch_slaughter' => $batchSlaughters->sum('count'),
                'individual_slaughter' => $individualSlaughters->count(),
            ]
        ];
    }

    private function getProductionReportData($startDate, $endDate, $location)
    {
        $query = ProductionRecord::with(['user'])
            ->whereBetween('production_date', [$startDate, $endDate]);

        return [
            'title' => 'Production Report',
            'data' => $query->orderBy('production_date', 'desc')->get(),
            'summary' => [
                'total_records' => $query->count(),
                'total_quantity' => $query->sum('quantity'),
                'average_daily' => $query->avg('quantity'),
            ]
        ];
    }

    private function getMedicineReportData($startDate, $endDate, $location)
    {
        $query = MedicineRecord::with(['user'])
            ->whereBetween('medicine_date', [$startDate, $endDate]);

        return [
            'title' => 'Medicine Report',
            'data' => $query->orderBy('medicine_date', 'desc')->get(),
            'summary' => [
                'total_records' => $query->count(),
                'total_cost' => $query->sum('cost'),
            ]
        ];
    }
}
