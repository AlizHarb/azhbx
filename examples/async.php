<?php

require __DIR__ . '/../vendor/autoload.php';

use AlizHarb\AzHbx\Engine;

$engine = new Engine([
    'views_path' => __DIR__ . '/views',
    'cache_path' => __DIR__ . '/../cache',
]);

// Create Async Template
$asyncPath = __DIR__ . '/views/themes/default/async.hbx';
if (!file_exists($asyncPath)) {
    file_put_contents($asyncPath, '
<h1>Async Data Loading</h1>
<p>User: {{ user }}</p>
<p>Status: {{ status }}</p>
');
}

// Simulate async data fetching using Fiber
$fiber = new Fiber(function () use ($engine) {
    $data = [
        'user' => 'Loading...',
        'status' => 'Pending'
    ];
    
    // In a real async scenario, we might yield here while waiting for I/O
    Fiber::suspend($data);
    
    // Simulate completion
    $data['user'] = 'Async User';
    $data['status'] = 'Loaded';
    
    return $data;
});

// Start the fiber
$initialData = $fiber->start();
echo "Initial State: " . $engine->render('async', $initialData) . "\n";

// Resume the fiber (simulating callback or event loop tick)
try {
    $finalData = $fiber->resume();
    if ($finalData === null) {
        // If fiber finished, it should return data. If it suspended again, it returns value passed to suspend.
        // If it finished and returned null, then data is null.
        // Let's check if fiber is terminated
        if ($fiber->isTerminated()) {
            $finalData = $fiber->getReturn();
        } else {
            $finalData = [];
        }
    }
} catch (Throwable $e) {
    echo "Fiber Error: " . $e->getMessage() . "\n";
    $finalData = [];
}
echo "Final State: " . $engine->render('async', $finalData) . "\n";
