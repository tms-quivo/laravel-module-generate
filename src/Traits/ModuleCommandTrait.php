<?php
namespace Tomosia\LaravelModuleGenerate\Traits;

use function Laravel\Prompts\text;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

trait ModuleCommandTrait
{
    /**
     * Prepare options.
     *
     * @return void
     */
    protected function prepareOptions()
    {
        if (! $this->option('module')) {
            $this->input->setOption('module', text('Please enter the name of the module', required: true));
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
                ['module', 'mdl', InputOption::VALUE_OPTIONAL, 'The name of the module'],
                ['container', 'ctn', InputOption::VALUE_OPTIONAL, 'The name of the container'],
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
        if (null !== $this->option('module')) {
            return config('module-generator.module_namespace') . '\\';
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
        if ($this->option('module') === null) {
            return parent::getDefaultNamespace($rootNamespace);
        }
        $type = str($this->type)->plural()->toString();

        return sprintf(
            '%s\\%s\\%s',
            $rootNamespace,
            $this->option('module'),
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
            '{{ module }}' => $this->option('module'),
            '{{ name }}'   => class_basename($this->argument('name')),
        ];

        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $this->replaceGeneral($stub, $this->getSubNamespace())
        );
    }
}
