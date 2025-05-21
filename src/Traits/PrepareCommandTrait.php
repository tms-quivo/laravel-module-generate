<?php
namespace Tomosia\LaravelModuleGenerate\Traits;

use function Laravel\Prompts\text;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

trait PrepareCommandTrait
{
    /**
     * The path of class generated.
     *
     * @var string
     */
    protected string $filePath;

    /**
     * Prepare options.
     *
     * @return void
     */
    protected function prepareOptions()
    {
        if (! $this->option('module')) {
            $name = text('Please enter the name of the module', required: true);

            $this->input->setOption('module', $name);
        }
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return array_merge(
            [
                ['module', 'm', InputOption::VALUE_REQUIRED, 'The name of the module'],
                ['collection', 'cl', InputOption::VALUE_NONE, 'The resource collection'],
            ],
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
        return sprintf("%s\\", config('module-generator.module_namespace'));
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
     * Get the snake case type.
     *
     * @return string
     */
    protected function getSnakeType(): string
    {
        return str($this->type)->snake()->toString();
    }

    protected function getConfigPath(): string
    {
        return config("module-generator.paths.{$this->getSnakeType()}");
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        if (null !== $this->option('module')) {
            return sprintf('%s\\%s\\%s', $rootNamespace, $this->option('module'), $this->getConfigPath());
        }

        return sprintf('%s\\%s', $rootNamespace, $this->getConfigPath());
    }

    /**
     * Get the namespace for the given name.
     *
     * @return string
     */
    protected function getClassNamespace(): string
    {
        $path = $this->getConfigPath();
        $path = str($path)->append('\\' . $this->getSubNamespace())->chopEnd('\\')->toString();

        return sprintf(
            '%s\%s\%s',
            trim($this->rootNamespace(), '\\'),
            $this->option('module'),
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
        if (! Str::contains($this->argument('name'), '\\')) {
            return '';
        }

        return str_replace('\\' . $this->getClassName(), '', $this->argument('name'));
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . '/../Generators/Stubs/' . str($this->type)->studly()->toString() . 'Stub.stub';
    }

    /**
     * Get the class name for the given name.
     *
     * @return string
     */
    protected function getClassName(): string
    {
        return class_basename($this->argument('name'));
    }

    /**
     * Replace the stub variables for the generator.
     *
     * @param string $stub
     * @return string
     */
    protected function replaceStub(string $stub): string
    {
        $stub = $this->replaceGeneral($stub, $this->getSubNamespace());

        return str_replace(
            [
                '{{ module }}',
                '{{ name }}',
            ],
            [
                $this->option('module'),
                $this->getClassName(),
            ],
            $stub
        );
    }
}
