<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\BatchFeed;
use App\Models\FeedType;

echo "Testing Feed Type Relationship Fix\n";
echo "==================================\n\n";

// Check if we have any feed types
$feedTypes = FeedType::all();
echo "Available Feed Types:\n";
foreach ($feedTypes as $feedType) {
    echo "- {$feedType->name}\n";
}
echo "\n";

// Check if we have any batch feeds
$batchFeeds = BatchFeed::take(5)->get();
echo "Sample Batch Feeds:\n";
foreach ($batchFeeds as $feed) {
    echo "- Feed Date: {$feed->feed_date}, Type: {$feed->feed_type}, Quantity: {$feed->quantity}\n";

    // Test the new relationship
    $feedTypeRelation = $feed->feedType;
    if ($feedTypeRelation) {
        echo "  ✅ Found matching FeedType: {$feedTypeRelation->name} ({$feedTypeRelation->unit})\n";
    } else {
        echo "  ❌ No matching FeedType found for '{$feed->feed_type}'\n";
    }
}

echo "\nTest completed!\n";
