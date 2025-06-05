<?php

namespace Tomosia\LaravelModuleGenerate\Traits;

use Illuminate\Support\Str;

trait PrepareModelTrait
{
	/**
	 * Qualify the model class name.
	 *
	 * @param string $model
	 * @return string
	 */
	protected function qualifyModel(string $model): string
    {
        $container = $this->option('container');
        $rootNamespace = $this->getRootNamespace($container);
        $model = $this->normalizeModelName($model);

        if (Str::startsWith($model, $rootNamespace)) {
            return $model;
        }

        return $this->buildModelPath($rootNamespace, $model);
    }

    /**
     * Get the root namespace based on container.
     *
     * @param string|null $container
     * @return string
     */
    private function getRootNamespace(?string $container): string
    {
        if ($container === null) {
            return $this->rootNamespace();
        }

        return sprintf('%s%s\\', $this->rootNamespace(), $container);
    }

    /**
     * Normalize the model name by removing leading slashes and converting forward slashes to backslashes.
     *
     * @param string $model
     * @return string
     */
    private function normalizeModelName(string $model): string
    {
        return str_replace('/', '\\', ltrim($model, '\\/'));
    }

    /**
     * Build the final model path.
     *
     * @param string $rootNamespace
     * @param string $model
     * @return string
     */
    private function buildModelPath(string $rootNamespace, string $model): string
    {
        return is_dir(app_path('Models'))
            ? $rootNamespace . 'Models\\' . $model
            : $rootNamespace . $model;
    }
}
