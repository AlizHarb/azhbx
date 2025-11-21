<?php
/**
 * AzHbx Documentation Website
 * A premium, modern documentation viewer.
 */

// Configuration
$config = [
    'title' => 'AzHbx',
    'version' => '1.0.0',
    'repo_url' => 'https://github.com/AlizHarb/azhbx',
];

// Navigation Structure
$menu = [
    'Getting Started' => [
        'index.md' => 'Introduction',
        'installation.md' => 'Installation & Config',
    ],
    'Core Concepts' => [
        'basics.md' => 'Basic Syntax',
        'control-structures.md' => 'Control Structures',
        'layouts-and-partials.md' => 'Layouts & Partials',
        'helpers.md' => 'Helpers',
        'plugins.md' => 'Plugins & Extensions',
    ],
    'Architecture' => [
        'themes-and-modules.md' => 'Themes & Modules',
        'advanced.md' => 'Advanced Topics',
    ]
];

// Determine current page
$page = $_GET['page'] ?? 'index.md';
$currentPageTitle = 'Documentation';

// Flatten menu for validation and title lookup
$flatMenu = [];
foreach ($menu as $section => $items) {
    foreach ($items as $file => $title) {
        $flatMenu[$file] = $title;
        if ($file === $page) {
            $currentPageTitle = $title;
        }
    }
}

// Security check
if (!array_key_exists($page, $flatMenu)) {
    $page = 'index.md';
    $currentPageTitle = 'Introduction';
}

$content = file_get_contents(__DIR__ . '/' . $page);
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $currentPageTitle ?> - AzHbx Documentation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/tokyo-night-dark.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        slate: {
                            850: '#1e293b',
                            900: '#0f172a',
                            950: '#020617',
                        },
                        primary: {
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap');
        
        body { font-family: 'Inter', sans-serif; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }

        /* Typography Enhancements */
        .prose { max-width: 65ch; margin: 0 auto; }
        .prose h1 { color: #f8fafc; font-weight: 800; letter-spacing: -0.025em; margin-bottom: 1.5rem; font-size: 2.5rem; line-height: 1.1; }
        .prose h2 { color: #f1f5f9; font-weight: 700; letter-spacing: -0.025em; margin-top: 3rem; margin-bottom: 1rem; font-size: 1.75rem; border-bottom: 1px solid #1e293b; padding-bottom: 0.5rem; }
        .prose h3 { color: #e2e8f0; font-weight: 600; margin-top: 2rem; margin-bottom: 0.75rem; font-size: 1.25rem; }
        .prose p { color: #94a3b8; line-height: 1.75; margin-bottom: 1.25rem; font-size: 1.05rem; }
        .prose ul { list-style-type: disc; padding-left: 1.5rem; color: #94a3b8; margin-bottom: 1.25rem; }
        .prose li { margin-bottom: 0.5rem; }
        .prose strong { color: #f1f5f9; font-weight: 600; }
        .prose code { background: #1e293b; color: #e2e8f0; padding: 0.2em 0.4em; border-radius: 0.375rem; font-size: 0.875em; font-family: 'JetBrains Mono', monospace; border: 1px solid #334155; }
        .prose pre { background: #0f172a; border-radius: 0.75rem; overflow: hidden; margin: 1.5rem 0; border: 1px solid #1e293b; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
        .prose pre code { background: transparent; padding: 1.25rem; border: none; color: #e2e8f0; font-size: 0.875rem; line-height: 1.7; display: block; overflow-x: auto; }
        .prose a { color: #818cf8; text-decoration: none; border-bottom: 1px solid transparent; transition: border-color 0.2s; }
        .prose a:hover { border-bottom-color: #818cf8; }
        .prose blockquote { border-left: 4px solid #6366f1; padding-left: 1rem; font-style: italic; color: #cbd5e1; background: rgba(99, 102, 241, 0.1); padding: 1rem; border-radius: 0 0.5rem 0.5rem 0; margin: 1.5rem 0; }
        
        /* Table Styles */
        .prose table { width: 100%; border-collapse: collapse; margin: 1.5rem 0; }
        .prose th { text-align: left; padding: 0.75rem; border-bottom: 2px solid #334155; color: #f1f5f9; font-weight: 600; }
        .prose td { padding: 0.75rem; border-bottom: 1px solid #1e293b; color: #94a3b8; }
        .prose tr:last-child td { border-bottom: none; }
    </style>
</head>
<body class="bg-slate-950 text-slate-200 h-screen flex flex-col overflow-hidden">

    <!-- Top Navigation -->
    <header class="h-16 bg-slate-900/80 backdrop-blur-md border-b border-slate-800 flex-shrink-0 z-50">
        <div class="container mx-auto px-4 h-full flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-purple-600 rounded-lg flex items-center justify-center shadow-lg shadow-primary-500/20">
                    <span class="text-white font-bold text-lg">A</span>
                </div>
                <span class="font-bold text-xl tracking-tight text-white">AzHbx</span>
                <span class="px-2 py-0.5 rounded-full bg-slate-800 text-xs font-medium text-slate-400 border border-slate-700">v<?= $config['version'] ?></span>
            </div>
            <div class="flex items-center gap-4">
                <a href="../examples/index.php" class="text-sm font-medium text-slate-400 hover:text-white transition-colors">Live Examples</a>
                <a href="<?= $config['repo_url'] ?>" class="text-slate-400 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                </a>
            </div>
        </div>
    </header>

    <div class="flex flex-1 overflow-hidden container mx-auto max-w-7xl">
        
        <!-- Sidebar Navigation -->
        <aside class="w-64 hidden lg:block overflow-y-auto border-r border-slate-800 py-8 pr-6">
            <nav class="space-y-8">
                <?php foreach ($menu as $section => $items): ?>
                    <div>
                        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3 px-3"><?= $section ?></h3>
                        <ul class="space-y-1">
                            <?php foreach ($items as $file => $title): ?>
                                <li>
                                    <a href="?page=<?= urlencode($file) ?>" 
                                       class="block px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 <?= $page === $file ? 'bg-primary-500/10 text-primary-400 border-l-2 border-primary-500' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' ?>">
                                        <?= htmlspecialchars($title) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto py-8 px-4 lg:px-12 scroll-smooth relative">
            <div class="max-w-3xl mx-auto pb-20">
                <!-- Breadcrumbs -->
                <div class="flex items-center text-sm text-slate-500 mb-8">
                    <span>Docs</span>
                    <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-slate-300"><?= htmlspecialchars($currentPageTitle) ?></span>
                </div>

                <!-- Content Rendered Here -->
                <article id="content" class="prose prose-invert">
                    <!-- JS will inject content here -->
                </article>

                <!-- Page Navigation -->
                <div class="mt-16 pt-8 border-t border-slate-800 flex justify-between">
                    <?php
                    // Find prev/next links
                    $keys = array_keys($flatMenu);
                    $currentIndex = array_search($page, $keys);
                    $prev = $currentIndex > 0 ? $keys[$currentIndex - 1] : null;
                    $next = $currentIndex < count($keys) - 1 ? $keys[$currentIndex + 1] : null;
                    ?>
                    
                    <div>
                        <?php if ($prev): ?>
                            <a href="?page=<?= $prev ?>" class="group flex flex-col">
                                <span class="text-xs text-slate-500 font-medium mb-1 group-hover:text-primary-400 transition-colors">Previous</span>
                                <span class="text-lg font-semibold text-slate-300 group-hover:text-white transition-colors">
                                    &larr; <?= $flatMenu[$prev] ?>
                                </span>
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="text-right">
                        <?php if ($next): ?>
                            <a href="?page=<?= $next ?>" class="group flex flex-col">
                                <span class="text-xs text-slate-500 font-medium mb-1 group-hover:text-primary-400 transition-colors">Next</span>
                                <span class="text-lg font-semibold text-slate-300 group-hover:text-white transition-colors">
                                    <?= $flatMenu[$next] ?> &rarr;
                                </span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>

        <!-- Table of Contents (Right Sidebar) -->
        <aside class="w-64 hidden xl:block overflow-y-auto py-8 pl-6">
            <div class="sticky top-8">
                <h4 class="text-sm font-semibold text-slate-200 mb-4">On this page</h4>
                <ul id="toc" class="space-y-2 text-sm border-l border-slate-800">
                    <!-- JS will populate this -->
                </ul>
            </div>
        </aside>

    </div>

    <!-- Footer -->
    <footer class="border-t border-slate-800 bg-slate-900/50 backdrop-blur-sm flex-shrink-0">
        <div class="container mx-auto px-4 py-6">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="text-sm text-slate-400">
                    <p>© <?= date('Y') ?> <a href="https://github.com/AlizHarb" class="text-primary-400 hover:text-primary-300 transition-colors">Ali Harb</a>. All rights reserved.</p>
                    <p class="text-xs text-slate-500 mt-1">Licensed under the MIT License</p>
                </div>
                <div class="flex items-center gap-4">
                    <a href="<?= $config['repo_url'] ?>" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 text-sm text-slate-400 hover:text-white transition-colors group">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                        <span>View on GitHub</span>
                    </a>
                    <span class="text-slate-600">|</span>
                    <a href="https://github.com/AlizHarb/azhbx/issues" target="_blank" rel="noopener noreferrer" class="text-sm text-slate-400 hover:text-white transition-colors">Report Issue</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Data for JS -->
    <script id="markdown-content" type="text/template"><?= $content ?></script>

    <script>
        // Get markdown content
        const markdown = document.getElementById('markdown-content').textContent;
        
        // Configure Marked
        marked.setOptions({
            highlight: function(code, lang) {
                const language = hljs.getLanguage(lang) ? lang : 'plaintext';
                return hljs.highlight(code, { language }).value;
            },
            langPrefix: 'hljs language-'
        });

        // Render
        const contentDiv = document.getElementById('content');
        contentDiv.innerHTML = marked.parse(markdown);

        // Generate Table of Contents
        const tocList = document.getElementById('toc');
        const headers = contentDiv.querySelectorAll('h2, h3');
        
        if (headers.length === 0) {
            tocList.innerHTML = '<li class="pl-4 text-slate-500 italic">No subsections</li>';
        } else {
            headers.forEach((header, index) => {
                // Create ID for header
                const id = 'header-' + index;
                header.id = id;
                
                const li = document.createElement('li');
                const a = document.createElement('a');
                a.href = '#' + id;
                a.textContent = header.textContent;
                a.className = 'block pl-4 border-l border-transparent hover:border-primary-500 hover:text-primary-400 text-slate-500 transition-colors py-1';
                
                if (header.tagName === 'H3') {
                    a.classList.add('ml-4');
                }
                
                li.appendChild(a);
                tocList.appendChild(li);
            });
        }
        
        // Highlight active TOC item on scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = entry.target.id;
                    document.querySelectorAll('#toc a').forEach(link => {
                        link.classList.remove('text-primary-400', 'border-primary-500');
                        link.classList.add('text-slate-500', 'border-transparent');
                        if (link.getAttribute('href') === '#' + id) {
                            link.classList.remove('text-slate-500', 'border-transparent');
                            link.classList.add('text-primary-400', 'border-primary-500');
                        }
                    });
                }
            });
        }, { rootMargin: '-100px 0px -66%' });

        headers.forEach(header => observer.observe(header));
    </script>

</body>
</html>
