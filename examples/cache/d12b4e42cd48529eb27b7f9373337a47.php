<?php
return function($data, $engine) {
$helpers = $engine->getHelperRegistry()->getAll();
$buffer = '';
$buffer .= '
<h1>Component Demo</h1>

';
if (isset($helpers['Alert'])) {
    $buffer .= $helpers['Alert']($data, ['fn' => function($data) use ($engine, $helpers) { $buffer = '';
$buffer .= '
    <strong>Success!</strong> Operation completed successfully.
';
return $buffer; }, 'args' => array (
), 'hash' => array (
  'type' => 'success',
  'icon' => 'check',
)], $engine);
} else {
    // Helper 'Alert' not found
}
$buffer .= '

';
if (isset($helpers['Alert'])) {
    $buffer .= $helpers['Alert']($data, ['fn' => function($data) use ($engine, $helpers) { $buffer = '';
$buffer .= '
    <strong>Error!</strong> Something went wrong.
';
return $buffer; }, 'args' => array (
), 'hash' => array (
  'type' => 'danger',
  'icon' => 'times',
)], $engine);
} else {
    // Helper 'Alert' not found
}
$buffer .= '
';
return $buffer;
};