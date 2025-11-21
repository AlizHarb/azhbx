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
        $tokens = $this->tokenize($template);
        return $this->buildAst($tokens);
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

        // parts structure:
        // [0] => Text
        // [1] => Group 1 (Raw content)
        // [2] => Group 2 (Normal content)
        // [3] => Text
        // ...
        
        // However, if a group is not matched, is it returned as empty string or null?
        // preg_split returns empty string for unmatched groups if they are part of the match?
        // Actually, let's be careful.
        // If I match {{{foo}}}, Group 1 is "foo", Group 2 is unmatched.
        // If I match {{bar}}, Group 1 is unmatched, Group 2 is "bar".
        
        // The array will be flattened.
        // Text, G1, G2, Text, G1, G2...
        
        // Let's iterate carefully.
        $i = 0;
        $count = count($parts);
        while ($i < $count) {
            // Text
            $text = $parts[$i][0];
            if ($text !== '') {
                $tokens[] = ['type' => 'text', 'value' => $text];
            }
            $i++;
            
            if ($i >= $count) break;
            
            // Next are the capture groups.
            // Since we have 2 groups in regex, we expect 2 elements in parts array representing the captures?
            // Wait, preg_split returns the captures interleaved.
            // If regex matches, it returns the captures.
            // If {{{foo}}} matches:
            // parts[i] = "foo" (Group 1)
            // parts[i+1] = "" (Group 2, unmatched? or maybe null?)
            // Actually, let's check if unmatched groups are returned.
            // Yes, they are returned as empty strings or skipped depending on engine?
            // Usually empty strings.
            
            // But wait, if I use `|`, only one branch matches.
            // Does preg_split return captures from the other branch?
            // Yes, usually.
            
            // Let's assume we get 2 captures.
            $raw = $parts[$i][0] ?? null;
            $normal = $parts[$i+1][0] ?? null;
            
            // Check offsets to see which one matched?
            // Or just check which one is not empty/null (and valid).
            // But empty string is valid content `{{}}`.
            // Offset -1 means not matched.
            
            $rawOffset = $parts[$i][1];
            $normalOffset = $parts[$i+1][1];
            
            if ($rawOffset !== -1) {
                // Raw matched
                $tokens[] = $this->parseTag($raw, true);
            } elseif ($normalOffset !== -1) {
                // Normal matched
                $tokens[] = $this->parseTag($normal, false);
            }
            
            $i += 2; // Skip both capture groups
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
        $args = array_map(function($arg) {
            // Strip quotes if present
            if ((str_starts_with($arg, '"') && str_ends_with($arg, '"')) || 
                (str_starts_with($arg, "'") && str_ends_with($arg, "'"))) {
                return substr($arg, 1, -1);
            }
            return $arg;
        }, $parts);

        return [
            'type' => $type,
            'name' => $name,
            'args' => $args
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
                    'children' => []
                ];
                $current[] = $node;
                // Push reference to children array of the new node onto stack
                $stack[] = &$current[count($current) - 1]['children'];
            } elseif ($token['type'] === 'block_end') {
                array_pop($stack);
            } else {
                $current[] = $token;
            }
        }

        return $root;
    }
}
