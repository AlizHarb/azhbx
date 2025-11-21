<?php

require __DIR__ . '/../vendor/autoload.php';

use AlizHarb\AzHbx\Engine;

$engine = new Engine([
    'views_path' => __DIR__ . '/views',
    'cache_path' => __DIR__ . '/../cache',
]);

$viewPath = __DIR__ . '/views/themes/default/control_structures.hbx';
if (!file_exists($viewPath)) {
    file_put_contents($viewPath, '
<h1>Control Structures</h1>

<h2>If / Else</h2>
{{#if isLoggedIn}}
    <p>Welcome back, user!</p>
{{else}}
    <p>Please log in.</p>
{{/if}}

<h2>Unless</h2>
{{#unless isAdmin}}
    <p>You are not an admin.</p>
{{/unless}}

<h2>Each (Array)</h2>
<ul>
{{#each items}}
    <li>{{ this }}</li>
{{/each}}
</ul>

<h2>Each (Object)</h2>
<ul>
{{#each user}}
    <li>{{ @key }}: {{ this }}</li>
{{/each}}
</ul>

<h2>With</h2>
{{#with profile}}
    <p>Bio: {{ bio }}</p>
    <p>Location: {{ location }}</p>
{{/with}}
');
}

$data = [
    'isLoggedIn' => true,
    'isAdmin' => false,
    'items' => ['Apple', 'Banana', 'Cherry'],
    'user' => [
        'username' => 'jdoe',
        'email' => 'jdoe@example.com'
    ],
    'profile' => [
        'bio' => 'Software Engineer',
        'location' => 'New York'
    ]
];

echo $engine->render('control_structures', $data);
