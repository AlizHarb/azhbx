<?php

namespace AzHbx\Bridge;

interface AdapterInterface
{
    /**
     * Render a template with data.
     *
     * @param string $template The template name or path.
     * @param array $data The data to pass to the template.
     * @return string The rendered output.
     */
    public function render(string $template, array $data = []): string;

    /**
     * Register a global variable.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function share(string $key, mixed $value): void;
}
