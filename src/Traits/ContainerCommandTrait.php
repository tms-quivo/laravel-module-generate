<?php
namespace Tomosia\LaravelModuleGenerate\Traits;

use function Laravel\Prompts\text;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

trait ContainerCommandTrait
{
    /**
     * Prepare options.
     *
     * @return void
     */
    protected function prepareOptions()
    {
        if (! $this->option('container')) {
            $this->input->setOption('container', text('Please enter the name of the container', required: true));
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
                ['container', 'ctn', InputOption::VALUE_OPTIONAL, 'The name of the container'],
                ['module', 'mdl', InputOption::VALUE_OPTIONAL, 'The name of the module'],
                ['table', 'tb', InputOption::VALUE_OPTIONAL, 'The table name'],
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
        if (null !== $this->option('container')) {
            return config('module-generator.container_namespace') . '\\';
        }

        return parent::rootNamespace();
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        if ($this->option('container') === null) {
            return parent::getDefaultNamespace($rootNamespace);
        }
        $type = str($this->type)->plural()->toString();

        return sprintf(
            '%s\\%s\\%s',
            $rootNamespace,
            $this->option('container'),
            getConfigPath($this->type, $type)
        );
    }

    /**
     * Get the namespace for the given name.
     *
     * @return string
     */
    protected function getClassNamespace(): string
    {
        $type = str($this->type)->plural()->toString();
        $path = str(getConfigPath($this->type, $type))->append('\\' . $this->getSubNamespace())->chopEnd('\\')->toString();

        return sprintf(
            '%s\\%s\\%s',
            trim($this->rootNamespace(), '\\'),
            $this->option('container'),
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

        return ! Str::contains($name, '\\')
        ? ''
        : str_replace('\\' . class_basename($name), '', $name);
    }

    /**
     * Replace the stub variables for the generator.
     *
     * @param string $stub
     * @return string
     */
    protected function replaceStub(string $stub): string
    {
        $replacements = [
            '{{ container }}' => $this->option('container'),
            '{{ name }}'      => class_basename($this->argument('name')),
        ];

        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $this->replaceGeneral($stub, $this->getSubNamespace())
        );
    }
}
