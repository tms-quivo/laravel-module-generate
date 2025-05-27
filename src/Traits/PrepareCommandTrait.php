<?php
namespace Tomosia\LaravelModuleGenerate\Traits;

trait PrepareCommandTrait
{
    /**
     * The path of class generated.
     *
     * @var string
     */
    protected string $filePath;

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
        $stub = __DIR__ . '/../Generators/Stubs/' . str($this->type)->snake('-')->lower()->toString() . '.stub';
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
