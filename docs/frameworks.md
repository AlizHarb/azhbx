# Framework Integration

AzHbx provides native adapters for popular PHP frameworks, making it easy to use as a drop-in replacement for Blade or Twig.

## Laravel

The Laravel adapter integrates AzHbx into the `view` service, allowing you to use `.hbx` templates alongside Blade.

### Installation

1.  Register the Service Provider in `config/app.php` (if not auto-discovered):

    ```php
    'providers' => [
        // ...
        AlizHarb\AzHbx\Bridge\Laravel\AzHbxServiceProvider::class,
    ],
    ```

2.  Add the Facade alias (optional):

    ```php
    'aliases' => [
        // ...
        'AzHbx' => AlizHarb\AzHbx\Bridge\Laravel\AzHbx::class,
    ],
    ```

### Usage

Create templates in `resources/views` with the `.hbx` extension.

```html
<!-- resources/views/welcome.hbx -->
<h1>Hello, {{ name }}!</h1>
```

Controller:

```php
public function index()
{
    return view('welcome', ['name' => 'Laravel']);
}
```

You can also use the Facade directly:

```php
use AzHbx;

echo AzHbx::render('welcome', ['name' => 'Laravel']);
```

---

## Symfony

The Symfony adapter integrates AzHbx as a Bundle.

### Installation

1.  Register the Bundle in `config/bundles.php`:

    ```php
    return [
        // ...
        AlizHarb\AzHbx\Bridge\Symfony\AzHbxBundle::class => ['all' => true],
    ];
    ```

2.  Configure the bundle in `config/packages/azhbx.yaml`:

    ```yaml
    azhbx:
      views_path: "%kernel.project_dir%/templates"
      cache_path: "%kernel.cache_dir%/azhbx"
      default_theme: "default"
    ```

### Usage

Inject the `Engine` service into your controllers:

```php
use AlizHarb\AzHbx\Engine;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    public function index(Engine $engine): Response
    {
        $html = $engine->render('home', ['name' => 'Symfony']);
        return new Response($html);
    }
}
```
