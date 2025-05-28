<?php
namespace Tomosia\LaravelModuleGenerate\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Livewire;
use ReflectionClass;
use Symfony\Component\Finder\SplFileInfo;

class LivewireComponentServiceProvider extends ServiceProvider
{
    private const DEFAULT_LIVEWIRE_NAMESPACE = 'Livewire';
    private const DEFAULT_MODULE_NAMESPACE   = 'Modules';

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerModuleComponents();
    }

    /**
     * Register the module components.
     */
    protected function registerModuleComponents()
    {
        $config = $this->getModuleConfig();

        if (! File::isDirectory($config['modulePath'])) {
            return;
        }

        collect(File::directories($config['modulePath']))
            ->each(function (string $module) use ($config) {
                $this->registerModule($module, $config);
            });
    }

    /**
     * Get the module config.
     */
    private function getModuleConfig(): array
    {
        return [
            'livewireNamespace' => config('module-generator.livewire.namespace', self::DEFAULT_LIVEWIRE_NAMESPACE),
            'modulePath'        => config('module-generator.module_path', base_path('modules')),
            'moduleNamespace'   => config('module-generator.module_namespace', self::DEFAULT_MODULE_NAMESPACE),
        ];
    }

    /**
     * Register the module.
     */
    private function registerModule(string $module, array $config): void
    {
        $directory   = $this->buildComponentDirectory($module, $config['livewireNamespace']);
        $namespace   = $this->buildComponentNamespace($module, $config['moduleNamespace'], $config['livewireNamespace']);
        $aliasPrefix = strtolower(class_basename($module)) . '::';

        $this->registerComponentDirectory($directory, $namespace, $aliasPrefix);
    }

    /**
     * Build the component directory.
     */
    private function buildComponentDirectory(string $module, string $livewireNamespace): string
    {
        return Str::of($module)
            ->append('/' . $livewireNamespace)
            ->replace(['\\'], '/')
            ->toString();
    }

    /**
     * Build the component namespace.
     */
    private function buildComponentNamespace(string $module, string $moduleNamespace, string $livewireNamespace): string
    {
        return $moduleNamespace . '\\' . class_basename($module) . '\\' . $livewireNamespace;
    }

    /**
     * Register the component directory.
     */
    protected function registerComponentDirectory(string $directory, string $namespace, string $aliasPrefix = ''): bool
    {
        if (! File::isDirectory($directory)) {
            return false;
        }

        $this->processComponentFiles($directory, $namespace, $aliasPrefix);
        
        return true;
    }

    /**
     * Process the component files.
     */
    private function processComponentFiles(string $directory, string $namespace, string $aliasPrefix): void
    {
        collect(File::allFiles($directory))
            ->map(fn(SplFileInfo $file) => $this->buildClassName($file, $namespace))
            ->filter(fn(string $class) => $this->isValidComponent($class))
            ->each(fn(string $class) => $this->registerComponent($class, $namespace, $aliasPrefix));
    }

    /**
     * Build the class name.
     */
    private function buildClassName(SplFileInfo $file, string $namespace): string
    {
        return Str::of($namespace)
            ->append('\\', $file->getRelativePathname())
            ->replace('/', '\\')
            ->replace('.php', '')
            ->toString();
    }

    /**
     * Check if the class is a valid component.
     */
    private function isValidComponent(string $class): bool
    {
        return is_subclass_of($class, Component::class) && ! (new ReflectionClass($class))->isAbstract();
    }

    /**
     * Register the component.
     */
    private function registerComponent(string $class, string $namespace, string $aliasPrefix): void
    {
        $alias = $this->buildComponentAlias($class, $namespace, $aliasPrefix);

        if (Str::endsWith($class, ['\Index', '\index'])) {
            Livewire::component(Str::beforeLast($alias, '.index'), $class);
        }

        Livewire::component($alias, $class);
    }

    /**
     * Build the component alias.
     */
    private function buildComponentAlias(string $class, string $namespace, string $aliasPrefix): string
    {
        return $aliasPrefix . Str::of($class)
            ->after($namespace . '\\')
            ->replace(['/', '\\'], '.')
            ->explode('.')
            ->map([Str::class, 'kebab'])
            ->implode('.');
    }
}
