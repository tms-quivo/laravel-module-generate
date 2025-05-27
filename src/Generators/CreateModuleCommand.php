<?php
namespace Tomosia\LaravelModuleGenerate\Generators;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateModuleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:create {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new module';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Module';

    public function handle()
    {
        $moduleName = $this->getModuleName();
        $modulePath = $this->getModulePath($moduleName);

        if ($this->moduleExists($modulePath)) {
            return false;
        }

        $this->createModuleStructure($modulePath);
        $this->createProviders($moduleName);

        $namespace = config('module-generator.module_namespace') . '\\' . $moduleName;
        $this->components->info(sprintf('%s [%s] created successfully.', $this->type, $namespace));
        
        return true;
    }

    /**
     * Get the module name from input.
     *
     * @return string
     */
    protected function getModuleName(): string
    {
        return str($this->argument('name'))->trim()->ucfirst()->toString();
    }

    /**
     * Get the full module path.
     *
     * @param string $moduleName
     * @return string
     */
    protected function getModulePath(string $moduleName): string
    {
        return config('module-generator.module_path') . '/' . $moduleName;
    }

    /**
     * Check if module already exists.
     *
     * @param string $modulePath
     * @return bool
     */
    protected function moduleExists(string $modulePath): bool
    {
        if (File::exists($modulePath)) {
            $this->components->error("The module \"{$this->getModuleName()}\" already exists.");
            
            return true;
        }

        return false;
    }

    /**
     * Create the module directory structure.
     *
     * @param string $modulePath
     * @return void
     */
    protected function createModuleStructure(string $modulePath): void
    {
        $this->createScaffoldFolders($modulePath);
        $this->createViewFile($modulePath);
        $this->createRouteFile($modulePath);
    }

    /**
     * Create scaffold folders for the module.
     *
     * @param string $modulePath
     * @return void
     */
    protected function createScaffoldFolders(string $modulePath): void
    {
        foreach (config('module-generator.m_scaffold_folders', []) as $structure) {
            File::makeDirectory(
                $modulePath . '/' . str_replace('\\', '/', $structure),
                0755,
                true,
                true
            );
        }
    }

    /**
     * Create the view file for the module.
     *
     * @param string $modulePath
     * @return void
     */
    protected function createViewFile(string $modulePath): void
    {
        $viewPath = $modulePath . '/resources/views/index.blade.php';
        
        if (!File::exists($viewPath)) {
            File::ensureDirectoryExists(dirname($viewPath));
            File::put($viewPath, '<div>' . PHP_EOL . '</div>' . PHP_EOL);
        }
    }

    /**
     * Create the route file for the module.
     *
     * @param string $modulePath
     * @return void
     */
    protected function createRouteFile(string $modulePath): void
    {
        $routePath = $modulePath . '/routes/web.php';
        $stubPath = __DIR__ . '/Stubs/route.stub';

        if (file_exists($stubPath)) {
            File::ensureDirectoryExists(dirname($routePath));
            File::put($routePath, File::get($stubPath));
        }
    }

    /**
     * Create service providers for the module.
     *
     * @param string $moduleName
     * @return void
     */
    protected function createProviders(string $moduleName): void
    {
        $providers = [
            'route' => 'RouteServiceProvider',
            'view' => 'ViewServiceProvider',
            'app' => $moduleName . 'ServiceProvider',
        ];

        foreach ($providers as $key => $provider) {
            $this->call('module:make-provider', [
                'name' => $provider,
                '--module' => $moduleName,
                '--provider' => $key,
            ]);
        }
    }
}
