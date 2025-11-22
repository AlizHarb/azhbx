<?php

use AlizHarb\AzHbx\Engine;

beforeEach(function () {
    $this->viewsPath = __DIR__ . '/../../views';
    $this->themePath = $this->viewsPath . '/themes/default';
    $this->cachePath = __DIR__ . '/../../cache';

    if (!is_dir($this->viewsPath)) {
        mkdir($this->viewsPath, 0777, true);
    }
    if (!is_dir($this->viewsPath . '/themes')) {
        mkdir($this->viewsPath . '/themes', 0777, true);
    }
    if (!is_dir($this->themePath)) {
        mkdir($this->themePath, 0777, true);
    }
    if (!is_dir($this->cachePath)) {
        mkdir($this->cachePath, 0777, true);
    }

    $this->engine = new Engine([
        'views_path' => $this->viewsPath,
        'cache_path' => $this->cachePath,
        'default_theme' => 'default',
    ]);
});

afterEach(function () {
    // Cleanup
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($this->viewsPath, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($files as $fileinfo) {
        $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
        $todo($fileinfo->getRealPath());
    }
    if (is_dir($this->viewsPath)) {
        rmdir($this->viewsPath);
    }

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($this->cachePath, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($files as $fileinfo) {
        $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
        $todo($fileinfo->getRealPath());
    }
    if (is_dir($this->cachePath)) {
        rmdir($this->cachePath);
    }
});

test('it renders recursive partials (menu tree)', function () {
    // Create recursive partial
    $partialContent = '<ul>{{#each items as |item|}}<li>{{item.name}}{{#if item.children}}{{> menu items=item.children}}{{/if}}</li>{{/each}}</ul>';
    file_put_contents($this->themePath . '/menu.hbx', $partialContent);

    // Create main template
    file_put_contents($this->themePath . '/tree.hbx', '{{> menu items=menu}}');

    $data = [
        'menu' => [
            [
                'name' => 'Home',
                'children' => [],
            ],
            [
                'name' => 'Products',
                'children' => [
                    ['name' => 'Electronics', 'children' => []],
                    ['name' => 'Books', 'children' => [
                        ['name' => 'Fiction', 'children' => []],
                        ['name' => 'Non-Fiction', 'children' => []],
                    ]],
                ],
            ],
        ],
    ];

    $output = $this->engine->render('tree', $data);

    // Normalize output for comparison (remove whitespace/newlines if needed, but here we expect exact string)
    // The expected output structure:
    $expected = '<ul><li>Home</li><li>Products<ul><li>Electronics</li><li>Books<ul><li>Fiction</li><li>Non-Fiction</li></ul></li></ul></li></ul>';

    expect($output)->toBe($expected);
});

test('it renders complex dashboard layout', function () {
    // Layout
    $layout = '<html><head><title>{{title}}</title></head><body><header>{{> header}}</header><main>{{#block content}}{{/block}}</main><footer>{{> footer}}</footer></body></html>';
    file_put_contents($this->themePath . '/layout.hbx', $layout);

    // Partials
    file_put_contents($this->themePath . '/header.hbx', '<h1>App: {{appName}}</h1>');
    file_put_contents($this->themePath . '/footer.hbx', '<p>&copy; {{year}}</p>');

    // Dashboard View
    $dashboard = '{{#extend layout}}{{#block content}}<h2>Welcome, {{user.name}}</h2><div class="stats">{{#each stats as |stat|}}<div class="stat">{{stat.label}}: {{stat.value}}</div>{{/each}}</div>{{/block}}{{/extend}}';
    file_put_contents($this->themePath . '/dashboard.hbx', $dashboard);

    $data = [
        'title' => 'My Dashboard',
        'appName' => 'AzHbx App',
        'year' => '2025',
        'user' => ['name' => 'Alice'],
        'stats' => [
            ['label' => 'Visits', 'value' => 100],
            ['label' => 'Sales', 'value' => 500],
        ],
    ];

    $output = $this->engine->render('dashboard', $data);

    expect($output)->toContain('<h1>App: AzHbx App</h1>');
    expect($output)->toContain('<h2>Welcome, Alice</h2>');
    expect($output)->toContain('<div class="stat">Visits: 100</div>');
    expect($output)->toContain('<div class="stat">Sales: 500</div>');
    expect($output)->toContain('<p>&copy; 2025</p>');
});
