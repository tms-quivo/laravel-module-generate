<?php
namespace Tomosia\LaravelModuleGenerate\Traits;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;
use Tomosia\LaravelModuleGenerate\Constants\ModuleLayer;

trait PrepareCommandTrait
{
    use PromptsForMissingOptions;

    /**
     * The path of class generated.
     *
     * @var string
     */
    protected string $filePath;

    /**
     * Prepare options.
     *
     * @return array
     */
    protected function prepareOptions(): array
    {
        if ($this->layer === ModuleLayer::MODULE) {
            return [
                ['module', null, InputOption::VALUE_REQUIRED, 'The name of the module'],
                ['container', null, InputOption::VALUE_OPTIONAL, 'The name of the container'],
                ['provider', null, InputOption::VALUE_OPTIONAL, 'The options of the module'],
            ];
        }

        return [
            ['container', null, InputOption::VALUE_REQUIRED, 'The name of the container'],
            ['module', null, InputOption::VALUE_OPTIONAL, 'The name of the module'],
            ['table', null, InputOption::VALUE_OPTIONAL, 'The table name'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return array_merge(
            $this->prepareOptions(),
            parent::getOptions(),
        );
    }

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace(): string
    {
        if (! isset($this->layer)) {
            return parent::rootNamespace();
        }

        return config(sprintf('module-generator.%s_namespace', strtolower($this->layer)), 'Modules') . '\\';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        if (! isset($this->layer)) {
            return parent::getDefaultNamespace($rootNamespace);
        }

        $default = Str::plural($this->type);

        return sprintf(
            '%s\\%s\\%s',
            $rootNamespace,
            $this->option(strtolower($this->layer)),
            getConfigNamespace($this->type, $default)
        );
    }

    /**
     * Get the namespace for the given name.
     *
     * @return string
     */
    protected function getClassNamespace(): string
    {
        $default = Str::plural($this->type);
        $path    = Str::of(getConfigNamespace($this->type, $default))
            ->append('\\' . $this->getSubNamespace())
            ->chopEnd('\\')
            ->toString();

        return sprintf(
            '%s\\%s\\%s',
            trim($this->rootNamespace(), '\\'),
            $this->option(strtolower($this->layer)),
            $path
        );
    }

    /**
     * Get the namespace for the given name.
     *
     * @return string
     */
    protected function getSubNamespace(): string
    {
        $name = $this->argument('name');

        return Str::doesntContain($name, '\\') ? str_replace('\\' . class_basename($name), '', $name) : '';
    }

    /**
     * Replace the stub variables for the generator.
     *
     * @param string $stub
     * @return string
     */
    protected function replaceStub(string $stub): string
    {
        $layer        = strtolower($this->layer);
        $replacements = [
            "{{ $layer }}" => $this->option($layer),
            '{{ name }}'   => class_basename($this->argument('name')),
        ];

        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $this->replaceGeneral($stub, $this->getSubNamespace())
        );
    }

    /**
     * Get the destination class path.
     *
     * @param string $name
     * @return string
     */
    protected function getPath($name): string
    {
        $this->filePath = $this->laravel->basePath() . '/' . str_replace('\\', '/', $name) . '.php';

        return $this->filePath;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        $stub = __DIR__ . '/../Generators/stubs/' . str($this->type)->snake('-')->lower()->toString() . '.stub';
        if (file_exists($stub)) {
            return $stub;
        }

        return parent::getStub();
    }

    /**
     * Format the generated code using Laravel Pint.
     */
    protected function formatCodeWithPint(): void
    {
        if (isset($this->filePath)) {
            exec(base_path('vendor/bin/pint') . " {$this->filePath}");
        }
    }
}
