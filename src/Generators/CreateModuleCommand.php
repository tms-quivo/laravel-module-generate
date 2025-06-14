<?php
namespace Tomosia\LaravelModuleGenerate\Generators;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class CreateModuleCommand extends Command implements PromptsForMissingInput
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

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * Create a new command instance.
     *
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     * @return void
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $moduleName = $this->getModuleName();
        $modulePath = $this->getModulePath($moduleName);
        $namespace = ucfirst(str_replace('/', '\\', $modulePath));

        if ($this->moduleExists($modulePath)) {
            return SymfonyCommand::FAILURE;
        }

        $this->createModuleStructure($modulePath);
        $this->createProviders($moduleName);

        $this->components->info(sprintf('%s [%s] created successfully.', $this->type, $namespace));
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
        if ($this->filesystem->exists($modulePath)) {
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
            $path = $modulePath . '/' . str_replace('\\', '/', $structure);
            $this->filesystem->makeDirectory(
                $path,
                0755,
                true,
                true
            );

            if (config('module-generator.stubs.gitkeep', false)) {
                $this->generateGitKeep($path);
            }
        }
    }

    /**
     * Generate git keep to the specified path.
     */
    public function generateGitKeep(string $path)
    {
        $this->filesystem->put($path.'/.gitkeep', '');
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

        if (!$this->filesystem->exists($viewPath)) {
            $this->filesystem->ensureDirectoryExists(dirname($viewPath));
            $this->filesystem->put($viewPath, '<div>' . PHP_EOL . '</div>' . PHP_EOL);
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
        $stubPath = __DIR__ . '/stubs/route.stub';

        if (file_exists($stubPath)) {
            $this->filesystem->ensureDirectoryExists(dirname($routePath));
            $this->filesystem->put($routePath, $this->filesystem->get($stubPath));
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
            $this->callSilent('module:make-provider', [
                'name' => $provider,
                '--module' => $moduleName,
                '--provider' => $key,
            ]);
        }
    }
}
