<?php

use Illuminate\Foundation\Vite;
use Illuminate\Support\Facades\Vite as ViteFacade;

if (! function_exists('getConfigNamespace')) {
    /**
     * Get the config path.
     *
     * @param string $type
     * @return string|null
     */
    function getConfigNamespace(string $type, ?string $default = null): ?string
    {
        $type = str($type)->snake()->toString();

        return config('module-generator.namespaces.' . $type, $default);
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

if (! function_exists('includeRouteFiles')) {

    /**
     * @param mixed $folder Folder
     */
    function includeRouteFiles($folder)
    {
        includeFilesInFolder($folder);
    }
}

if (! function_exists('includeFilesInFolder')) {
    /**
     * Loops through a folder and requires all PHP files
     * Searches sub-directories as well.
     *
     * @param mixed $folder Folder
     */
    function includeFilesInFolder($folder)
    {
        try {
            $rdi = new RecursiveDirectoryIterator($folder);
            $it = new RecursiveIteratorIterator($rdi);

            while ($it->valid()) {
                if (! $it->isDot() && $it->isFile() && $it->isReadable() && $it->current()->getExtension() === 'php') {
                    require $it->key();
                }

                $it->next();
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
