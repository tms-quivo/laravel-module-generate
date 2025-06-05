<?php

namespace Tomosia\LaravelModuleGenerate\Traits;

use Illuminate\Support\Str;

trait ModuleCommandTrait
{
	/**
     * Get the module name
     *
     * @return string|null
     */
    protected function getModule(): ?string
    {
        return $this->hasOption('module') ? $this->option('module') : null;
    }

	/**
	 * Get the container name
	 *
	 * @return string|null
	 */
	protected function getContainer(): ?string
	{
		return $this->hasOption('container') ? $this->option('container') : null;
	}

    /**
     * Get the module lower name
     *
     * @return string
     */
    protected function getModuleLowerName(): string
    {
        return Str::lower($this->getModule() ?? '');
    }

    /**
     * Get the module namespace
     *
     * @return string
     */
    protected function getModuleNamespace(): string
    {
        $module = $this->getModule();

        return config('module-generator.module_namespace', 'Modules') . '\\' . $module;
    }

    /**
     * Get the module path
     *
     * @return string
     */
    protected function getModulePath(): string
    {
        $module = $this->getModule();

        return config('module-generator.module_path', base_path('modules')) . '/' . $module;
    }

    /**
     * Get the module livewire namespace
     *
     * @return string
     */
    protected function getModuleLivewireNamespace(): string
    {
        return config('module-generator.livewire.namespace', 'Livewire');
    }

    /**
     * Get the namespace for the given class path
     *
     * @param string $classPath
     * @return string
     */
    protected function getLivewireNamespace($classPath): string
    {
        $classPath = Str::contains($classPath, '/') ? '/'.$classPath : '';

        $prefix = $this->getModuleNamespace() . '\\' . $this->getModuleLivewireNamespace();

        return (string) Str::of($classPath)
            ->beforeLast('/')
            ->prepend($prefix)
            ->replace(['/'], ['\\']);
    }

    /**
     * Get the module livewire view directory
     *
     * @return string
     */
    protected function getModuleLivewireViewDir(): string
    {
        $moduleLivewireViewDir = config('module-generator.livewire.view', 'resources/views/livewire');

        return $this->getModulePath() . '/' . $moduleLivewireViewDir;
    }

    /**
     * Check if the force option is set
     *
     * @return bool
     */
    protected function isForce(): bool
    {
        return $this->option('force') === true;
    }

    /**
     * Check if the inline option is set
     *
     * @return bool
     */
    protected function isInline(): bool
    {
        return $this->option('inline') === true;
    }
}
