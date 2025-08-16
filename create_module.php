<?php
// Usage: php create_module.php NamaModule

if ($argc < 2) {
    echo "Usage: php create_module.php NamaModule\n";
    exit(1);
}

$moduleName = ucfirst($argv[1]);
$moduleLower = strtolower($argv[1]);

$basePath = __DIR__ . "/application/apps/{$moduleLower}";

// Buat folder controllers, models, views
@mkdir($basePath . "/controllers", 0777, true);
@mkdir($basePath . "/models", 0777, true);
@mkdir($basePath . "/views", 0777, true);

// Template controller
$controllerTemplate = <<<PHP
<?php defined('BASEPATH') OR exit('No direct script access allowed');

class {$moduleName} extends MY_Controller
{
    public function index()
    {
        \$this->load->view('{$moduleLower}/v_{$moduleLower}');
    }
}
PHP;

// Template model
$modelTemplate = <<<PHP
<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_{$moduleName} extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
}
PHP;

// Template view
$viewTemplate = <<<HTML
<h1>{$moduleName} View</h1>
<p>Welcome to the {$moduleName} module!</p>
HTML;

// Simpan file
file_put_contents($basePath . "/controllers/{$moduleName}.php", $controllerTemplate);
file_put_contents($basePath . "/models/M_{$moduleName}.php", $modelTemplate);
file_put_contents($basePath . "/views/v_{$moduleLower}.php", $viewTemplate);

echo "Module '{$moduleName}' created successfully in application/modules/{$moduleLower}\n";
