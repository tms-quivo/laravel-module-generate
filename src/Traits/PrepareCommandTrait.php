<?php
namespace Tomosia\LaravelModuleGenerate\Traits;

use Illuminate\Support\Str;

trait PrepareCommandTrait
{
    /**
     * Get the namespace for the given name.
     *
     * @return string
     */
    protected function getClassNamespace(): string
    {
        $path = config('module-generator.paths.' . strtolower($this->type));
        $path = str($path)->append('\\' . $this->getSubNamespace())->chopEnd('\\')->toString();

        return sprintf(
            '%s\%s\%s',
            trim($this->rootNamespace(), '\\'),
            $this->option('module'),
            $path
        );
    }

    /**
     * Get the sub namespace for the given name.
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
        return __DIR__ . '/../Generators/Stubs/' . ucfirst($this->type) . 'Stub.stub';
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
     * Replace the stub.
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
