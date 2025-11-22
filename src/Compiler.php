<?php

declare(strict_types=1);

namespace AlizHarb\AzHbx;

/**
 * Template Compiler
 *
 * Compiles AzHbx templates into optimized PHP code for fast execution.
 * Handles caching and automatic recompilation when templates change.
 *
 * @package AlizHarb\AzHbx
 * @author  Ali Harb <harbzali@gmail.com>
 * @license MIT
 * @link    https://github.com/AlizHarb/azhbx
 */
class Compiler
{
    /**
     * Template parser instance
     *
     * @var Parser
     */
    private Parser $parser;

    /**
     * Path to cache directory for compiled templates
     *
     * @var string
     */
    private string $cachePath;

    /**
     * Debug mode flag
     *
     * @var bool
     */
    private bool $debug;

    /**
     * Initialize the compiler
     *
     * @param array<string, mixed> $config Configuration array containing:
     *                                     - delimiters: Template delimiters (default: ['{{', '}}'])
     *                                     - cache_path: Path for compiled templates
     *                                     - debug: Enable debug mode (default: false)
     */
    public function __construct(array $config)
    {
        $this->parser = new Parser($config['delimiters'] ?? ['{{', '}}']);
        $this->cachePath = $config['cache_path'];
        $this->debug = $config['debug'] ?? false;

        if (!is_dir($this->cachePath)) {
            mkdir($this->cachePath, 0755, true);
        }
    }

    /**
     * Compile a template file to PHP
     *
     * Checks cache and recompiles only if template has changed.
     *
     * @param string $templatePath Full path to template file
     *
     * @return string Path to compiled PHP file
     */
    public function compile(string $templatePath): string
    {
        if ($this->debug) {
            // In debug mode, check file modification time (Hot Reload)
            $hash = md5($templatePath . filemtime($templatePath));
        } else {
            // In production, just hash the path (Performance)
            // This assumes cache is cleared on deploy
            $hash = md5($templatePath);
        }

        $compiledFile = $this->cachePath . '/' . $hash . '.php';

        if (file_exists($compiledFile)) {
            return $compiledFile;
        }

        $template = file_get_contents($templatePath);
        $ast = $this->parser->parse($template);
        $php = $this->generatePhp($ast);

        file_put_contents($compiledFile, $php);

        return $compiledFile;
    }

    /**
     * Generate PHP code from AST
     *
     * Converts the parsed Abstract Syntax Tree into executable PHP code.
     *
     * @param array<int, array<string, mixed>> $ast Abstract Syntax Tree from parser
     *
     * @return string Generated PHP code
     */
    private function generatePhp(array $ast): string
    {
        $code = "<?php\n";
        $code .= "return function(\$data, \$engine) {\n";
        $code .= "\$helpers = \$engine->getHelperRegistry()->getAll();\n";
        $code .= "\$buffer = '';\n";
        $code .= $this->compileNodes($ast);
        $code .= "return \$buffer;\n";
        $code .= "};";

        return $code;
    }

    /**
     * Compile AST nodes into PHP code
     *
     * Recursively processes nodes and generates corresponding PHP code.
     * Handles text, variables, raw output, and block helpers.
     *
     * @param array<int, array<string, mixed>> $nodes Array of AST nodes
     *
     * @return string Generated PHP code for nodes
     */
    private function compileNodes(array $nodes): string
    {
        $code = "";
        foreach ($nodes as $node) {
            switch ($node['type']) {
                case 'text':
                    $code .= "\$buffer .= '" . addcslashes($node['value'], "'\\") . "';\n";

                    break;
                case 'variable':
                    $name = $node['name'];
                    $args = $node['args'] ?? [];
                    $hash = $node['hash'] ?? [];
                    $argsCode = var_export($args, true);
                    $hashCode = var_export($hash, true);

                    $code .= "if (isset(\$helpers['{$name}'])) {\n";
                    $code .= "    \$buffer .= \$helpers['{$name}'](\$data, ['args' => {$argsCode}, 'hash' => {$hashCode}], \$engine);\n";
                    $code .= "} elseif ('{$name}' === 'this') {\n";
                    $code .= "    \$buffer .= htmlspecialchars((string)\$data, ENT_QUOTES);\n";
                    $code .= "} else {\n";
                    $code .= "    // Optimized variable resolution\n";
                    $parts = explode('.', $name);
                    $first = array_shift($parts);

                    $code .= "    \$val = \$data['{$first}'] ?? null;\n";

                    foreach ($parts as $part) {
                        $code .= "    if (is_array(\$val)) {\n";
                        $code .= "        \$val = \$val['{$part}'] ?? null;\n";
                        $code .= "    } elseif (is_object(\$val)) {\n";
                        $code .= "        \$val = \$val->{$part} ?? null;\n";
                        $code .= "    } else {\n";
                        $code .= "        \$val = null;\n";
                        $code .= "    }\n";
                    }

                    $code .= "    \$buffer .= htmlspecialchars((string)(\$val ?? ''), ENT_QUOTES);\n";
                    $code .= "}\n";

                    break;
                case 'raw':
                    $name = $node['name'];
                    // Raw output, no escaping
                    $code .= "\$buffer .= (string)(\$data['{$name}'] ?? '');\n";

                    break;
                case 'block':
                    $name = $node['name'];
                    $args = $node['args'] ?? [];
                    $hash = $node['hash'] ?? [];
                    $argsCode = var_export($args, true);
                    $hashCode = var_export($hash, true);

                    $innerCode = $this->compileNodes($node['children']);
                    $innerFn = "function(\$data) use (\$engine, \$helpers) { \$buffer = '';\n{$innerCode}return \$buffer; }";

                    $code .= "if (isset(\$helpers['{$name}'])) {\n";
                    $code .= "    \$buffer .= \$helpers['{$name}'](\$data, ['fn' => {$innerFn}, 'args' => {$argsCode}, 'hash' => {$hashCode}], \$engine);\n";
                    $code .= "} else {\n";
                    $code .= "    // Helper '{$name}' not found\n";
                    $code .= "}\n";

                    break;
            }
        }

        return $code;
    }
}
