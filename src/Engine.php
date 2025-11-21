<?php

declare(strict_types=1);

namespace AlizHarb\AzHbx;

use AlizHarb\AzHbx\Exceptions\EngineException;

/**
 * AzHbx Template Engine
 *
 * A modern, production-ready PHP 8.5+ templating engine inspired by Handlebars.
 * Provides secure, fast, and flexible template rendering with theme and module support.
 *
 * @package AlizHarb\AzHbx
 * @author  Ali Harb <harbzali@gmail.com>
 * @license MIT
 * @link    https://github.com/AlizHarb/azhbx
 */
class Engine
{
    /**
     * Engine configuration array
     *
     * @var array<string, mixed>
     */
    private array $config;

    /**
     * Template compiler instance
     *
     * @var Compiler
     */
    private Compiler $compiler;

    /**
     * Template renderer instance
     *
     * @var Renderer
     */
    private Renderer $renderer;

    /**
     * Helper function registry
     *
     * @var HelperRegistry
     */
    private HelperRegistry $helperRegistry;

    /**
     * Partial template loader
     *
     * @var PartialLoader
     */
    private PartialLoader $partialLoader;

    /**
     * Theme manager for multi-theme support
     *
     * @var ThemeManager
     */
    private ThemeManager $themeManager;

    /**
     * Module manager for modular templates
     *
     * @var ModuleManager
     */
    private ModuleManager $moduleManager;

    /**
     * Template blocks storage for layouts
     *
     * @var array<string, string>
     */
    private array $blocks = [];

    /**
     * Whether currently capturing block content
     *
     * @var bool
     */
    private bool $capturing = false;

    /**
     * Initialize the template engine
     *
     * @param array<string, mixed> $config Configuration options:
     *                                     - views_path: Base path for templates (default: getcwd()/views)
     *                                     - cache_path: Path for compiled templates (default: getcwd()/cache)
     *                                     - extension: Template file extension (default: hbx)
     *                                     - delimiters: Opening/closing delimiters (default: ['{{', '}}'])
     *                                     - allow_php: Allow PHP code in templates (default: false)
     *                                     - default_theme: Default theme name (default: 'default')
     */
    public function __construct(array $config = [])
    {
        $this->config = array_merge([
            'views_path' => getcwd() . '/views',
            'cache_path' => getcwd() . '/cache',
            'extension' => 'hbx',
            'delimiters' => ['{{', '}}'],
            'allow_php' => false,
            'default_theme' => 'default',
        ], $config);

        $this->helperRegistry = new HelperRegistry();
        $this->partialLoader = new PartialLoader($this->config['views_path']);
        $this->themeManager = new ThemeManager($this->config['views_path'], $this->config['default_theme']);
        $this->moduleManager = new ModuleManager($this->config['views_path']);
        
        // Compiler and Renderer will be initialized lazily or here if they don't have circular deps
        // For now, let's assume they need the engine or config
        $this->compiler = new Compiler($this->config);
        $this->renderer = new Renderer($this);

        BuiltInHelpers::register($this);
    }

    /**
     * Render a template with data
     *
     * @param string               $template Template name (e.g., 'home', 'blog::post')
     * @param array<string, mixed> $data     Data to pass to the template
     *
     * @return string Rendered HTML output
     * @throws EngineException If template is not found
     */
    public function render(string $template, array $data = []): string
    {
        // Resolve template path
        $templatePath = $this->resolveTemplatePath($template);
        
        return $this->renderFile($templatePath, $data);
    }

    /**
     * Render a template file directly
     *
     * @param string               $templatePath Full path to template file
     * @param array<string, mixed> $data         Data to pass to the template
     *
     * @return string Rendered HTML output
     * @throws EngineException If template file doesn't exist
     */
    public function renderFile(string $templatePath, array $data = []): string
    {
        if (!file_exists($templatePath)) {
            throw new EngineException("Template not found: {$templatePath}");
        }

        // Compile if needed (check cache)
        $compiledPath = $this->compiler->compile($templatePath);

        // Render
        return $this->renderer->render($compiledPath, $data);
    }

    /**
     * Start capturing block content
     *
     * @return void
     */
    public function startCapture(): void
    {
        $this->capturing = true;
    }

    /**
     * End capturing block content
     *
     * @return void
     */
    public function endCapture(): void
    {
        $this->capturing = false;
    }

    /**
     * Check if currently capturing block content
     *
     * @return bool True if capturing, false otherwise
     */
    public function isCapturing(): bool
    {
        return $this->capturing;
    }

    /**
     * Set a block's content
     *
     * @param string $name    Block name
     * @param string $content Block content
     *
     * @return void
     */
    public function setBlock(string $name, string $content): void
    {
        $this->blocks[$name] = $content;
    }

    /**
     * Get a block's content
     *
     * @param string $name Block name
     *
     * @return string|null Block content or null if not set
     */
    public function getBlock(string $name): ?string
    {
        return $this->blocks[$name] ?? null;
    }

    /**
     * Set the active theme
     *
     * @param string $theme Theme name
     *
     * @return void
     */
    public function setTheme(string $theme): void
    {
        $this->themeManager->setActiveTheme($theme);
    }

    /**
     * Load and register a plugin
     *
     * Automatically registers helpers defined with #[Helper] attributes.
     *
     * @param Contracts\PluginInterface $plugin Plugin instance to load
     *
     * @return void
     */
    public function loadPlugin(Contracts\PluginInterface $plugin): void
    {
        $plugin->register($this);
        
        // Auto-register helpers via attributes if the plugin object has them
        $loader = new AttributeLoader($this);
        $loader->loadFromObject($plugin);
    }

    /**
     * Register a custom helper function
     *
     * @param string   $name   Helper name
     * @param callable $helper Helper function
     *
     * @return void
     */
    public function registerHelper(string $name, callable $helper): void
    {
        $this->helperRegistry->register($name, $helper);
    }

    /**
     * Resolve template path based on theme/module
     *
     * @param string $template Template name
     *
     * @return string Full path to template file
     */
    private function resolveTemplatePath(string $template): string
    {
        // Logic to resolve template based on theme/module
        // This is a simplified version, real logic will be in ThemeManager/ModuleManager
        
        // Check if it's a module template
        if (str_contains($template, '::')) {
            return $this->moduleManager->resolve($template);
        }

        // Use ThemeManager
        return $this->themeManager->resolve($template);
    }

    /**
     * Get the helper registry instance
     *
     * @return HelperRegistry Helper registry
     */
    public function getHelperRegistry(): HelperRegistry
    {
        return $this->helperRegistry;
    }

    /**
     * Get the partial loader instance
     *
     * @return PartialLoader Partial loader
     */
    public function getPartialLoader(): PartialLoader
    {
        return $this->partialLoader;
    }

    /**
     * Get configuration value(s)
     *
     * @param string|null $key Configuration key or null for all config
     *
     * @return mixed Configuration value or full config array
     */
    public function getConfig(?string $key = null): mixed
    {
        if ($key) {
            return $this->config[$key] ?? null;
        }
        return $this->config;
    }
}
