<?php
namespace Tomosia\LaravelModuleGenerate\Traits;

trait PrepareCommandTrait
{
    /**
     * Get the namespace for the given name.
     *
     * @return string
     */
    protected function getClassNamespace(): string
    {
        return sprintf(
            '%s\%s\%s',
            trim($this->rootNamespace(), '\\'),
            $this->option('module'),
            config('module-generator.paths.' . strtolower($this->type))
        );
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
        return $this->argument('name');
    }

    protected function replaceStub(string $stub): string
    {
        $stub = $this->replaceGeneral($stub);

        return str_replace(
            [
                '{{ module }}',
                '{{ name }}',
            ],
            [
                $this->option('module'),
                $this->argument('name'),
            ],
            $stub
        );
    }
}
