<?php
return function($data, $engine) {
$helpers = $engine->getHelperRegistry()->getAll();
$buffer = '';
$buffer .= '
<div class="alert alert-';
if (isset($helpers['type'])) {
    $buffer .= $helpers['type']($data, ['args' => array (
), 'hash' => array (
)], $engine);
} elseif ('type' === 'this') {
    $buffer .= htmlspecialchars((string)$data, ENT_QUOTES);
} else {
    // Optimized variable resolution
    $val = $data['type'] ?? null;
    $buffer .= htmlspecialchars((string)($val ?? ''), ENT_QUOTES);
}
$buffer .= '">
    <div class="alert-icon">
        ';
if (isset($helpers['Icon'])) {
    $buffer .= $helpers['Icon']($data, ['args' => array (
), 'hash' => array (
  'name' => 'icon',
)], $engine);
} elseif ('Icon' === 'this') {
    $buffer .= htmlspecialchars((string)$data, ENT_QUOTES);
} else {
    // Optimized variable resolution
    $val = $data['Icon'] ?? null;
    $buffer .= htmlspecialchars((string)($val ?? ''), ENT_QUOTES);
}
$buffer .= '
    </div>
    <div class="alert-content">
        ';
$buffer .= (string)($data['slot'] ?? '');
return $buffer;
};