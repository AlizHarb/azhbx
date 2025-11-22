<?php
return function($data, $engine) {
$helpers = $engine->getHelperRegistry()->getAll();
$buffer = '';
$buffer .= '<i class="fas fa-';
if (isset($helpers['name'])) {
    $buffer .= $helpers['name']($data, ['args' => array (
), 'hash' => array (
)], $engine);
} elseif ('name' === 'this') {
    $buffer .= htmlspecialchars((string)$data, ENT_QUOTES);
} else {
    // Optimized variable resolution
    $val = $data['name'] ?? null;
    $buffer .= htmlspecialchars((string)($val ?? ''), ENT_QUOTES);
}
$buffer .= '"></i>';
return $buffer;
};