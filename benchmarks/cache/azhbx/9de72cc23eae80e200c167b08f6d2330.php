<?php
return function($data, $engine) {
$buffer = '';
$buffer .= '<h1>';
$helper = $engine->getHelperRegistry()->get('title');
if ($helper) {
    $buffer .= $helper($data, ['args' => array (
)], $engine);
} elseif ('title' === 'this') {
    $buffer .= htmlspecialchars((string)$data, ENT_QUOTES);
    // Optimized variable resolution
    $val = $data['title'] ?? null;
    $buffer .= htmlspecialchars((string)($val ?? ''), ENT_QUOTES);
}
$buffer .= '</h1><ul>';
$helper = $engine->getHelperRegistry()->get('each');
if ($helper) {
    $buffer .= $helper($data, ['fn' => function($data) use ($engine) { $buffer = '';
$buffer .= '<li>';
$helper = $engine->getHelperRegistry()->get('item.name');
if ($helper) {
    $buffer .= $helper($data, ['args' => array (
)], $engine);
} elseif ('item.name' === 'this') {
    $buffer .= htmlspecialchars((string)$data, ENT_QUOTES);
    // Optimized variable resolution
    $val = $data['item'] ?? null;
    if (is_array($val)) {
        $val = $val['name'] ?? null;
    } elseif (is_object($val)) {
        $val = $val->name ?? null;
    } else {
        $val = null;
    }
    $buffer .= htmlspecialchars((string)($val ?? ''), ENT_QUOTES);
}
$buffer .= ': ';
$helper = $engine->getHelperRegistry()->get('item.price');
if ($helper) {
    $buffer .= $helper($data, ['args' => array (
)], $engine);
} elseif ('item.price' === 'this') {
    $buffer .= htmlspecialchars((string)$data, ENT_QUOTES);
    // Optimized variable resolution
    $val = $data['item'] ?? null;
    if (is_array($val)) {
        $val = $val['price'] ?? null;
    } elseif (is_object($val)) {
        $val = $val->price ?? null;
    } else {
        $val = null;
    }
    $buffer .= htmlspecialchars((string)($val ?? ''), ENT_QUOTES);
}
$buffer .= '</li>';
return $buffer; }, 'args' => array (
  0 => 'items',
  1 => 'as',
  2 => '|item|',
)], $engine);
} else {
    // Helper 'each' not found
}
$buffer .= '</ul>';
return $buffer;
};