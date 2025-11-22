<?php

declare(strict_types=1);

namespace AlizHarb\AzHbx;

/**
 * Template Parser
 *
 * Parses AzHbx template syntax into an Abstract Syntax Tree (AST).
 * Handles tokenization of template delimiters, variables, blocks, and raw output.
 *
 * @package AlizHarb\AzHbx
 * @author  Ali Harb <harbzali@gmail.com>
 * @license MIT
 * @link    https://github.com/AlizHarb/azhbx
 */
class Parser
{
    /**
     * Template delimiters (opening and closing)
     *
     * @var array{0: string, 1: string}
     */
    private array $delimiters;

    /**
     * Initialize the parser
     *
     * @param array{0: string, 1: string} $delimiters Opening and closing delimiters (default: ['{{', '}}'])
     */
    public function __construct(array $delimiters = ['{{', '}}'])
    {
        $this->delimiters = $delimiters;
    }

    /**
     * Parse template into Abstract Syntax Tree
     *
     * @param string $template Template content to parse
     *
     * @return array<int, array<string, mixed>> Abstract Syntax Tree
     */
    public function parse(string $template): array
    {
        $template = $this->preprocess($template);
        $tokens = $this->tokenize($template);

        return $this->buildAst($tokens);
    }

    /**
     * Preprocess template to handle component syntax
     *
     * Converts <x-Name ... /> to {{ Name ... }}
     * Converts <x-Name ...> to {{#Name ...}}
     * Converts </x-Name> to {{/Name}}
     *
     * @param string $template Raw template
     *
     * @return string Processed template
     */
    private function preprocess(string $template): string
    {
        // 1. Handle self-closing tags: <az-Name ... /> -> {{ Name ... }}
        $template = preg_replace_callback('/<az-([a-zA-Z0-9_.:-]+)\s*(.*?)\s*\/>/s', function ($matches) {
            $name = $matches[1];
            $attrs = $matches[2];

            return "{{ {$name} {$attrs} }}";
        }, $template);

        // 2. Handle opening tags: <az-Name ...> -> {{#Name ...}}
        $template = preg_replace_callback('/<az-([a-zA-Z0-9_.:-]+)\s*(.*?)>/s', function ($matches) {
            $name = $matches[1];
            $attrs = $matches[2];

            return "{{#{$name} {$attrs}}}";
        }, $template);

        // 3. Handle closing tags: </az-Name> -> {{/Name}}
        $template = preg_replace_callback('/<\/az-([a-zA-Z0-9_.:-]+)>/s', function ($matches) {
            $name = $matches[1];

            return "{{/{$name}}}";
        }, $template);

        // 4. Handle bare directives: @name ...
        // We need to distinguish between inline (@csrf) and block start (@auth).
        // We'll use a list of known block directives for now, or look for @endname.
        // Actually, simpler:
        // @endname -> {{/ @name }}
        // @name ... -> {{ @name ... }} OR {{# @name ... }}

        // Handle @end... first
        $template = preg_replace_callback('/@end([a-zA-Z0-9_]+)/', function ($matches) {
            return "{{/ @{$matches[1]} }}";
        }, $template);

        // Handle @else / @elseif (special cases)
        $template = str_replace('@else', '{{else}}', $template);
        $template = preg_replace('/@elseif\s*(.*)/', '{{else if $1}}', $template);

        // Handle other directives
        // We match lines starting with @, ignoring whitespace
        $template = preg_replace_callback('/(?m)^\s*@([a-zA-Z0-9_]+)(.*)$/', function ($matches) {
            $name = $matches[1];
            $args = $matches[2];

            // Skip if it's already processed (starts with end, else, elseif) - regex above handles end/else
            // But wait, the regex matches @end too if we are not careful.
            // The previous replace handled @end, so now we might match {{/ @name }}? No, that starts with {{.
            // We only match lines starting with @.

            // List of known block directives that need {{# }}
            $blockDirectives = ['auth', 'guest', 'env', 'section', 'push', 'prepend', 'once', 'verbatim'];

            if (in_array($name, $blockDirectives)) {
                return "{{# @{$name}{$args} }}";
            }

            return "{{ @{$name}{$args} }}";
        }, $template);

        return $template;
    }

    /**
     * Tokenize template into array of tokens
     *
     * Splits template by delimiters and identifies text, variables, raw output, and blocks.
     *
     * @param string $template Template content
     *
     * @return array<int, array<string, mixed>> Array of tokens
     */
    private function tokenize(string $template): array
    {
        $tokens = [];

        $start = preg_quote($this->delimiters[0], '/');
        $end = preg_quote($this->delimiters[1], '/');

        // Match {{{...}}} OR {{...}}
        // Note: {{{ must come first to match longest
        $pattern = '/' . $start . '{(.*?)' . '}' . $end . '|' . $start . '(.*?)' . $end . '/s';

        $parts = preg_split($pattern, $template, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_OFFSET_CAPTURE);

        // Iterate through parts
        // preg_split with DELIM_CAPTURE returns:
        // [Text, Offset]
        // [Capture1, Offset] (if matched)
        // [Capture2, Offset] (if matched)
        // [Text, Offset]
        // ...

        // However, if a capture group is not matched, it might not be in the array or be empty string?
        // With PREG_SPLIT_OFFSET_CAPTURE, every item is [string, offset].

        // Let's use a more robust loop.
        $count = count($parts);
        for ($i = 0; $i < $count; $i++) {
            // Even indices are always text (between delimiters)
            if ($i % 3 === 0) {
                $text = $parts[$i][0];
                if ($text !== '') {
                    $tokens[] = ['type' => 'text', 'value' => $text];
                }

                continue;
            }

            // Odd indices are captures.
            // We have 2 capture groups in regex: {{{...}}} (Group 1) and {{...}} (Group 2)
            // So the pattern repeats every 3 elements: Text, G1, G2, Text, G1, G2...

            // $parts[$i] is Group 1 (Raw)
            // $parts[$i+1] is Group 2 (Normal)

            $raw = $parts[$i][0];
            $rawOffset = $parts[$i][1];

            $normal = $parts[$i + 1][0] ?? ''; // Handle potential missing index if at end?
            $normalOffset = $parts[$i + 1][1] ?? -1;

            if ($rawOffset !== -1 && $raw !== '') {
                // Raw matched
                $tokens[] = $this->parseTag($raw, true);
            } elseif ($normalOffset !== -1 && $normal !== '') {
                // Normal matched
                $tokens[] = $this->parseTag($normal, false);
            }

            $i++; // Skip the next capture group as we processed both
        }

        return $tokens;
    }

    /**
     * Parse a tag into a token
     *
     * Handles block helpers, variables, raw output, and comments.
     *
     * @param string $content Tag content (without delimiters)
     * @param bool   $isRaw   Whether this is raw output (triple braces)
     *
     * @return array<string, mixed> Parsed token
     */
    private function parseTag(string $content, bool $isRaw = false): array
    {
        $content = trim($content);

        $type = 'variable';
        if ($isRaw) {
            $type = 'raw';
        }

        if (str_starts_with($content, '#')) {
            $type = 'block_start';
            $content = substr($content, 1);
        } elseif (str_starts_with($content, '/')) {
            $type = 'block_end';
            $content = substr($content, 1);
        } elseif (str_starts_with($content, '!')) {
            return ['type' => 'comment', 'value' => substr($content, 1)];
        }

        // Split by space but respect quotes
        // Simple regex for splitting by space ignoring quotes
        preg_match_all('/"[^"]*"|\'[^\']*\'|\S+/', $content, $matches);
        $parts = $matches[0] ?? [];

        if (empty($parts)) {
            return ['type' => 'text', 'value' => '']; // Should not happen
        }

        $name = array_shift($parts);
        $args = [];
        $hash = [];

        foreach ($parts as $part) {
            if (str_contains($part, '=')) {
                // Named argument (hash)
                [$key, $val] = explode('=', $part, 2);
                // We DO NOT strip quotes here anymore.
                // We let the helper/resolver decide if it's a literal (quoted) or path (unquoted).
                $hash[$key] = $val;
            } else {
                // Positional argument
                $args[] = $part;
            }
        }

        return [
            'type' => $type,
            'name' => $name,
            'args' => $args,
            'hash' => $hash,
        ];
    }

    /**
     * Build Abstract Syntax Tree from tokens
     *
     * Converts flat token array into nested tree structure for block helpers.
     *
     * @param array<int, array<string, mixed>> $tokens Array of tokens
     *
     * @return array<int, array<string, mixed>> Abstract Syntax Tree
     */
    private function buildAst(array $tokens): array
    {
        $root = [];
        $stack = [&$root];

        foreach ($tokens as $token) {
            $current = &$stack[count($stack) - 1];

            if ($token['type'] === 'block_start') {
                $node = [
                    'type' => 'block',
                    'name' => $token['name'],
                    'args' => $token['args'] ?? [],
                    'hash' => $token['hash'] ?? [],
                    'children' => [],
                ];
                $current[] = $node;
                // Push reference to children array of the new node onto stack
                $lastIndex = array_key_last($current);
                $stack[] = &$current[$lastIndex]['children'];
            } elseif ($token['type'] === 'block_end') {
                array_pop($stack);
            } else {
                $current[] = $token;
            }
        }

        return $root;
    }
}
