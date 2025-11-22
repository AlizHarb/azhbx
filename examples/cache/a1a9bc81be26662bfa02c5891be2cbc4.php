<?php
return function($data, $engine) {
$helpers = $engine->getHelperRegistry()->getAll();
$buffer = '';
$buffer .= '
<h1>Directive Demo</h1>

<form method="POST">
';
if (isset($helpers['@csrf'])) {
    $buffer .= $helpers['@csrf']($data, ['args' => array (
), 'hash' => array (
)], $engine);
} elseif ('@csrf' === 'this') {
    $buffer .= htmlspecialchars((string)$data, ENT_QUOTES);
} else {
    // Optimized variable resolution
    $val = $data['@csrf'] ?? null;
    $buffer .= htmlspecialchars((string)($val ?? ''), ENT_QUOTES);
}
$buffer .= '
';
if (isset($helpers['@method'])) {
    $buffer .= $helpers['@method']($data, ['args' => array (
  0 => 'PUT',
), 'hash' => array (
)], $engine);
} elseif ('@method' === 'this') {
    $buffer .= htmlspecialchars((string)$data, ENT_QUOTES);
} else {
    // Optimized variable resolution
    $val = $data['@method'] ?? null;
    $buffer .= htmlspecialchars((string)($val ?? ''), ENT_QUOTES);
}
$buffer .= '
    
    <button type="submit">Submit</button>
</form>
';
if (isset($helpers['@env'])) {
    $buffer .= $helpers['@env']($data, ['fn' => function($data) use ($engine, $helpers) { $buffer = '';
$buffer .= '
    <p>This is production environment.</p>
';
return $buffer; }, 'args' => array (
  0 => 'production',
), 'hash' => array (
)], $engine);
} else {
    // Helper '@env' not found
}
$buffer .= '
';
if (isset($helpers['@env'])) {
    $buffer .= $helpers['@env']($data, ['fn' => function($data) use ($engine, $helpers) { $buffer = '';
$buffer .= '
    <p>This is local environment.</p>
';
return $buffer; }, 'args' => array (
  0 => 'local',
), 'hash' => array (
)], $engine);
} else {
    // Helper '@env' not found
}
$buffer .= '
';
return $buffer;
};