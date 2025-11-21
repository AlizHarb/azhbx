<?php
/**
 * AzHbx Examples Index
 */

$examples = [
    'basic.php' => 'Basic Usage',
    'control_structures.php' => 'Control Structures',
    'layouts.php' => 'Layouts & Partials',
    'helpers.php' => 'Custom Helpers',
    'themes.php' => 'Themes',
    'modules.php' => 'Modules',
    'async.php' => 'Async Rendering',
    'plugins.php' => 'Plugins & Attributes',
    'no_composer.php' => 'Standalone (No Composer)',
];

$currentExample = $_GET['example'] ?? null;
$output = '';
$source = '';

if ($currentExample && array_key_exists($currentExample, $examples)) {
    $file = __DIR__ . '/' . $currentExample;
    if (file_exists($file)) {
        $source = file_get_contents($file);
        
        ob_start();
        include $file;
        $output = ob_get_clean();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AzHbx - Modern PHP 8.5 Templating</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script>hljs.highlightAll();</script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=JetBrains+Mono:wght@400;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        pre, code { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body class="bg-slate-900 text-slate-200 min-h-screen flex flex-col">

    <!-- Header -->
    <header class="bg-slate-800 border-b border-slate-700 sticky top-0 z-50 backdrop-blur-md bg-opacity-90">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-indigo-500 rounded-lg flex items-center justify-center shadow-lg shadow-indigo-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white tracking-tight">AzHbx</h1>
                    <p class="text-xs text-slate-400 font-medium">Modern PHP 8.5 Templating</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow container mx-auto px-6 py-8 flex flex-col lg:flex-row gap-8">
        
        <!-- Sidebar -->
        <aside class="w-full lg:w-1/4 flex-shrink-0">
            <div class="bg-slate-800 rounded-xl shadow-xl border border-slate-700 overflow-hidden sticky top-24">
                <div class="p-4 border-b border-slate-700 bg-slate-800/50">
                    <h2 class="font-semibold text-slate-200">Examples</h2>
                </div>
                <nav class="p-2 space-y-1">
                    <?php foreach ($examples as $file => $title): ?>
                        <a href="?example=<?= urlencode($file) ?>" 
                           class="block px-4 py-3 rounded-lg transition-all duration-200 group <?= $currentExample === $file ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:bg-slate-700 hover:text-white' ?>">
                            <div class="flex items-center justify-between">
                                <span class="font-medium"><?= htmlspecialchars($title) ?></span>
                                <?php if ($currentExample === $file): ?>
                                    <svg class="w-4 h-4 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                <?php endif; ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </nav>
            </div>
        </aside>

        <!-- Content Area -->
        <section class="w-full lg:w-3/4 space-y-6">
            <?php if ($currentExample): ?>
                
                <!-- Output Panel -->
                <div class="bg-slate-800 rounded-xl shadow-xl border border-slate-700 overflow-hidden">
                    <div class="p-4 border-b border-slate-700 flex justify-between items-center bg-slate-800/50">
                        <h2 class="font-semibold text-emerald-400">Rendered Output</h2>
                    </div>
                    <div class="p-6 bg-white text-slate-900 rounded-b-xl overflow-auto">
                        <?= $output ?: '<span class="text-slate-400 italic">No output returned.</span>' ?>
                    </div>
                </div>

                <!-- Source Code Panel -->
                <div class="bg-slate-800 rounded-xl shadow-xl border border-slate-700 overflow-hidden">
                    <div class="p-4 border-b border-slate-700 flex justify-between items-center bg-slate-800/50">
                        <h2 class="font-semibold text-blue-400">Source Code</h2>
                    </div>
                    <div class="relative group">
                        <pre><code class="language-php !bg-slate-900 !p-6 text-sm leading-relaxed"><?= htmlspecialchars($source) ?></code></pre>
                    </div>
                </div>

            <?php else: ?>
                
                <!-- Welcome State -->
                <div class="bg-slate-800 rounded-xl shadow-xl border border-slate-700 p-12 text-center">
                    <div class="w-20 h-20 bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2">Select an Example</h2>
                    <p class="text-slate-400 max-w-md mx-auto">Explore the features of <strong>AzHbx</strong>, the modern PHP 8.5 templating engine.</p>
                </div>

            <?php endif; ?>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-800 border-t border-slate-700 py-6 mt-auto">
        <div class="container mx-auto px-6 text-center text-slate-500 text-sm">
            &copy; <?= date('Y') ?> AzHbx. Built for PHP 8.5+.
        </div>
    </footer>

</body>
</html>
