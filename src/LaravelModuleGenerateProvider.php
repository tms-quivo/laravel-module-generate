<?php
namespace Tomosia\LaravelModuleGenerate;

use Illuminate\Support\ServiceProvider;
use Tomosia\LaravelModuleGenerate\Generators\Commands\ActionGeneratorCommand;
use Tomosia\LaravelModuleGenerate\Generators\Commands\ControllerGeneratorCommand;
use Tomosia\LaravelModuleGenerate\Generators\Commands\ModelGeneratorCommand;
use Tomosia\LaravelModuleGenerate\Generators\Commands\RequestGeneratorCommand;
use Tomosia\LaravelModuleGenerate\Generators\Commands\ResourceGeneratorCommand;

class LaravelModuleGenerateProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/module-generator.php', 'module-generator');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/module-generator.php' => config_path('module-generator.php'),
            ]);

            $this->commands([
                ControllerGeneratorCommand::class,
                RequestGeneratorCommand::class,
                ResourceGeneratorCommand::class,
                ModelGeneratorCommand::class,
                ActionGeneratorCommand::class,
            ]);
        }
    }
}
