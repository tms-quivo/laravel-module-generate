<?php
namespace Tomosia\LaravelModuleGenerate;

use Illuminate\Support\ServiceProvider;
use Tomosia\LaravelModuleGenerate\Generators\Commands\ActionGeneratorCommand;
use Tomosia\LaravelModuleGenerate\Generators\Commands\ChannelGeneratorCommand;
use Tomosia\LaravelModuleGenerate\Generators\Commands\ControllerGeneratorCommand;
use Tomosia\LaravelModuleGenerate\Generators\Commands\EventGeneratorCommand;
use Tomosia\LaravelModuleGenerate\Generators\Commands\JobGeneratorCommand;
use Tomosia\LaravelModuleGenerate\Generators\Commands\ListenerGeneratorCommand;
use Tomosia\LaravelModuleGenerate\Generators\Commands\LivewireGeneratorCommand;
use Tomosia\LaravelModuleGenerate\Generators\Commands\MailGeneratorCommand;
use Tomosia\LaravelModuleGenerate\Generators\Commands\ModelGeneratorCommand;
use Tomosia\LaravelModuleGenerate\Generators\Commands\NotificationGeneratorCommand;
use Tomosia\LaravelModuleGenerate\Generators\Commands\ObserverGeneratorCommand;
use Tomosia\LaravelModuleGenerate\Generators\Commands\PolicyGeneratorCommand;
use Tomosia\LaravelModuleGenerate\Generators\Commands\ProviderGeneratorCommand;
use Tomosia\LaravelModuleGenerate\Generators\Commands\RepositoryGeneratorCommand;
use Tomosia\LaravelModuleGenerate\Generators\Commands\RequestGeneratorCommand;
use Tomosia\LaravelModuleGenerate\Generators\Commands\ResourceGeneratorCommand;
use Tomosia\LaravelModuleGenerate\Generators\Commands\ScopeGeneratorCommand;
use Tomosia\LaravelModuleGenerate\Generators\CreateContainerCommand;
use Tomosia\LaravelModuleGenerate\Generators\CreateModuleCommand;
use Tomosia\LaravelModuleGenerate\Providers\LivewireComponentServiceProvider;

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
            ], 'module-generator');

            $this->publishes([
                __DIR__ . '/../scripts/vite-module-loader.js' => base_path('vite-module-loader.js'),
            ], 'vite-module');

            $this->commands($this->registeredCommands());
        }

        $this->registerProviders();
    }

    public function registeredCommands(): array
    {
        return [
            CreateContainerCommand::class,
            CreateModuleCommand::class,
            ControllerGeneratorCommand::class,
            RequestGeneratorCommand::class,
            ResourceGeneratorCommand::class,
            ModelGeneratorCommand::class,
            ActionGeneratorCommand::class,
            PolicyGeneratorCommand::class,
            ObserverGeneratorCommand::class,
            EventGeneratorCommand::class,
            ListenerGeneratorCommand::class,
            ScopeGeneratorCommand::class,
            MailGeneratorCommand::class,
            NotificationGeneratorCommand::class,
            JobGeneratorCommand::class,
            ChannelGeneratorCommand::class,
            RepositoryGeneratorCommand::class,
            ProviderGeneratorCommand::class,
            LivewireGeneratorCommand::class,
        ];
    }

    public function registerProviders()
    {
        $this->app->register(LivewireComponentServiceProvider::class);
    }
}
