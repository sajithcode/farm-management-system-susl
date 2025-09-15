<?php

// Simple test script to check if PDF templates compile without errors
require_once 'vendor/autoload.php';

use Barryvdh\DomPDF\Facade\Pdf;

// Mock data for testing
$testData = [
    'summary' => [
        'total_sales' => 150,
        'total_records' => 25,
        'total_quantity' => 500,
        'total_revenue' => 2500.50
    ],
    'data' => collect([])
];

$title = 'Test Sales Report';
$reportType = 'sales';
$dateFrom = '2025-09-01';
$dateTo = '2025-09-15';
$animalType = 'all';
$data = $testData;

try {
    echo "Testing PDF template compilation...\n";
    
    // Test if the view compiles without errors
    $view = view('reports.pdf', compact(
        'title', 'reportType', 'dateFrom', 'dateTo', 'animalType', 'data'
    ));
    
    $html = $view->render();
    echo "✓ PDF template compiled successfully!\n";
    echo "HTML length: " . strlen($html) . " characters\n";
    
    // Test PDF generation (without saving to file)
    $pdf = Pdf::loadHTML($html);
    echo "✓ PDF object created successfully!\n";
    
    // Get PDF content
    $pdfContent = $pdf->output();
    echo "✓ PDF content generated successfully!\n";
    echo "PDF size: " . strlen($pdfContent) . " bytes\n";
    
    echo "\n🎉 All tests passed! PDF generation should work properly.\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}