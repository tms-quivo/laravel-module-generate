<?php

use Illuminate\Foundation\Vite;
use Illuminate\Support\Facades\Vite as ViteFacade;

if (! function_exists('getSnakeType')) {
    /**
     * Get the snake case type.
     *
     * @param string $type
     * @return string
     */
    function getSnakeType(string $type): string
    {
        return str($type)->snake()->toString();
    }
}

if (! function_exists('getConfigPath')) {
    /**
     * Get the config path.
	 * 
	 * @param string $type
     * @return string|null
     */
    function getConfigPath(string $type, ?string $default = null): ?string
    {
        return config('module-generator.paths.' . getSnakeType($type), $default);
    }
}

if (! function_exists('module_vite')) {
    function module_vite(string $module, string $asset, ?string $hotFilePath = null): Vite
    {
        return ViteFacade::useHotFile($hotFilePath ?: storage_path('vite.hot'))
            ->useBuildDirectory($module)
            ->withEntryPoints([$asset]);
    }
}
