<?php

/**
 * Auto-generate PHPDoc blocks for AzHbx source files.
 *
 * This script scans the src/ directory and adds a class-level docblock
 * if missing. It also adds method-level docblocks for public methods.
 *
 * Usage: php scripts/add_phpdoc.php
 */

require __DIR__ . '/../vendor/autoload.php';

use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\NodeVisitor\NodeConnectingVisitor;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard as PrettyPrinter;

$srcDir = __DIR__ . '/../src';
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($srcDir));
$parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
$printer = new PrettyPrinter();

foreach ($files as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $code = file_get_contents($file->getRealPath());

        try {
            $stmts = $parser->parse($code);
        } catch (Exception $e) {
            continue; // skip files that cannot be parsed
        }
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new NameResolver());
        $traverser->addVisitor(new NodeConnectingVisitor());
        $traverser->traverse($stmts);
        $modified = false;
        foreach ($stmts as $stmt) {
            if ($stmt instanceof PhpParser\Node\Stmt\Class_) {
                // Add class docblock if missing
                if ($stmt->getDocComment() === null) {
                    $doc = new PhpParser\Comment\Doc("/**\n * {$stmt->name->name} class.\n *\n * @package AlizHarb\\AzHbx\n */");
                    $stmt->setDocComment($doc);
                    $modified = true;
                }
                // Add method docblocks for public methods
                foreach ($stmt->getMethods() as $method) {
                    if ($method->isPublic() && $method->getDocComment() === null) {
                        $params = [];
                        foreach ($method->params as $param) {
                            $type = $param->type ? $param->type->toString() . ' ' : '';
                            $params[] = "@param {$type}\${$param->var->name}";
                        }
                        $return = $method->getReturnType() ? $method->getReturnType()->toString() : 'mixed';
                        $docLines = array_merge(["/**", " * {$method->name->name} method.", " *"], $params, [" * @return {$return}", " */"]);
                        $doc = new PhpParser\Comment\Doc(implode("\n", $docLines));
                        $method->setDocComment($doc);
                        $modified = true;
                    }
                }
            }
        }
        if ($modified) {
            $newCode = $printer->prettyPrintFile($stmts);
            file_put_contents($file->getRealPath(), $newCode);
            echo "Updated {$file->getFilename()}\n";
        }
    }
}
