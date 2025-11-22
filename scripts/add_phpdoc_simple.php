<?php

/**
 * Simple script to add basic PHPDoc blocks to classes and public methods.
 * Works without external libraries.
 */

$srcDir = __DIR__ . '/../src';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($srcDir));

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $code = file_get_contents($file->getRealPath());
        $tokens = token_get_all($code);
        $output = '';
        $i = 0;
        $addedClassDoc = false;
        while ($i < count($tokens)) {
            $token = $tokens[$i];
            if (is_array($token) && $token[0] === T_CLASS) {
                // Look ahead for class name
                $j = $i + 1;
                while ($j < count($tokens) && is_array($tokens[$j]) && $tokens[$j][0] === T_WHITESPACE) {
                    $j++;
                }
                $className = '';
                if ($j < count($tokens) && is_array($tokens[$j]) && $tokens[$j][0] === T_STRING) {
                    $className = $tokens[$j][1];
                }
                // Check if previous token is a doc comment
                $hasDoc = false;
                $k = $i - 1;
                while ($k >= 0 && is_array($tokens[$k]) && $tokens[$k][0] === T_WHITESPACE) {
                    $k--;
                }
                if ($k >= 0 && is_array($tokens[$k]) && $tokens[$k][0] === T_DOC_COMMENT) {
                    $hasDoc = true;
                }
                if (!$hasDoc && $className) {
                    $doc = "/**\n * {$className} class.\n *\n * @package AlizHarb\\AzHbx\n */\n";
                    $output .= $doc;
                }
                $output .= is_array($token) ? $token[1] : $token;
                $i++;

                continue;
            }
            // Simple handling for public methods
            if (is_array($token) && $token[0] === T_PUBLIC) {
                // Look ahead for function keyword
                $j = $i + 1;
                while ($j < count($tokens) && is_array($tokens[$j]) && $tokens[$j][0] === T_WHITESPACE) {
                    $j++;
                }
                if ($j < count($tokens) && is_array($tokens[$j]) && $tokens[$j][0] === T_FUNCTION) {
                    // Find method name
                    $k = $j + 1;
                    while ($k < count($tokens) && is_array($tokens[$k]) && $tokens[$k][0] === T_WHITESPACE) {
                        $k++;
                    }
                    $methodName = '';
                    if ($k < count($tokens) && is_array($tokens[$k]) && $tokens[$k][0] === T_STRING) {
                        $methodName = $tokens[$k][1];
                    }
                    // Check for existing doc comment before public token
                    $hasDoc = false;
                    $p = $i - 1;
                    while ($p >= 0 && is_array($tokens[$p]) && $tokens[$p][0] === T_WHITESPACE) {
                        $p--;
                    }
                    if ($p >= 0 && is_array($tokens[$p]) && $tokens[$p][0] === T_DOC_COMMENT) {
                        $hasDoc = true;
                    }
                    if (!$hasDoc && $methodName) {
                        $doc = "/**\n * {$methodName} method.\n *\n * @return mixed\n */\n";
                        $output .= $doc;
                    }
                }
            }
            $output .= is_array($token) ? $token[1] : $token;
            $i++;
        }
        if ($output !== $code) {
            file_put_contents($file->getRealPath(), $output);
            echo "Updated {$file->getFilename()}\n";
        }
    }
}
