<?php
return function($data, $engine) {
$helpers = $engine->getHelperRegistry()->getAll();
$buffer = '';
$buffer .= '<h1>';
if (isset($helpers['title'])) {
    $buffer .= $helpers['title']($data, ['args' => array (
)], $engine);
} elseif ('title' === 'this') {
    $buffer .= htmlspecialchars((string)$data, ENT_QUOTES);
} else {
    // Optimized variable resolution
    $val = $data['title'] ?? null;
    $buffer .= htmlspecialchars((string)($val ?? ''), ENT_QUOTES);
}
$buffer .= '</h1><ul>';
if (isset($helpers['each'])) {
    $buffer .= $helpers['each']($data, ['fn' => function($data) use ($engine, $helpers) { $buffer = '';
$buffer .= '<li>';
if (isset($helpers['item.name'])) {
    $buffer .= $helpers['item.name']($data, ['args' => array (
)], $engine);
} elseif ('item.name' === 'this') {
    $buffer .= htmlspecialchars((string)$data, ENT_QUOTES);
} else {
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
if (isset($helpers['item.price'])) {
    $buffer .= $helpers['item.price']($data, ['args' => array (
)], $engine);
} elseif ('item.price' === 'this') {
    $buffer .= htmlspecialchars((string)$data, ENT_QUOTES);
} else {
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