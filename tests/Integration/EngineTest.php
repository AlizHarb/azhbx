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
    array_map('unlink', glob("$this->themePath/*.*"));
    if (is_dir($this->themePath)) {
        rmdir($this->themePath);
    }
    if (is_dir($this->viewsPath . '/themes')) {
        rmdir($this->viewsPath . '/themes');
    }
    if (is_dir($this->viewsPath)) {
        rmdir($this->viewsPath);
    }

    array_map('unlink', glob("$this->cachePath/*.*"));
    if (is_dir($this->cachePath)) {
        rmdir($this->cachePath);
    }
});

test('it renders basic variables', function () {
    file_put_contents($this->themePath . '/basic.hbx', 'Hello {{name}}!');

    $output = $this->engine->render('basic', ['name' => 'World']);

    expect($output)->toBe('Hello World!');
});

test('it renders nested variables', function () {
    file_put_contents($this->themePath . '/nested.hbx', '{{user.name}} is {{user.age}}');

    $output = $this->engine->render('nested', [
        'user' => ['name' => 'Ali', 'age' => 30],
    ]);

    expect($output)->toBe('Ali is 30');
});

test('it handles raw output', function () {
    file_put_contents($this->themePath . '/raw.hbx', '{{{html}}}');

    $output = $this->engine->render('raw', ['html' => '<strong>Bold</strong>']);

    expect($output)->toBe('<strong>Bold</strong>');
});

test('it supports each helper', function () {
    file_put_contents($this->themePath . '/list.hbx', '<ul>{{#each items as |item|}}<li>{{item}}</li>{{/each}}</ul>');

    $output = $this->engine->render('list', ['items' => ['A', 'B', 'C']]);

    expect($output)->toBe('<ul><li>A</li><li>B</li><li>C</li></ul>');
});

test('it supports if helper', function () {
    file_put_contents($this->themePath . '/condition.hbx', '{{#if show}}Shown{{/if}}{{#unless hide}}Not Hidden{{/unless}}');

    $output = $this->engine->render('condition', ['show' => true, 'hide' => false]);

    expect($output)->toBe('ShownNot Hidden');
});
