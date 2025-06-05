<?php
namespace Tomosia\LaravelModuleGenerate\Generators\Commands;

use Illuminate\Foundation\Console\ProviderMakeCommand;
use Tomosia\LaravelModuleGenerate\Constants\ModuleLayer;
use Tomosia\LaravelModuleGenerate\Traits\PrepareCommandTrait;

class ProviderGeneratorCommand extends ProviderMakeCommand
{
    use PrepareCommandTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'module:make-provider';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new provider class for provided module';

    /**
     * The layer of class generated.
     *
     * @var string
     */
    protected string $layer = ModuleLayer::MODULE;

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $name = match ($this->option('provider')) {
            'route' => 'provider.route',
            'view' => 'provider.view',
            'app' => 'provider.app',
            default => 'provider'
        };

        $stub = __DIR__ . '/../stubs/' . str($name)->snake('-')->lower()->toString() . '.stub';

        if (file_exists($stub)) {
            return $stub;
        }

        return parent::getStub();
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = parent::buildClass($name);

        return str_replace(
            [
                '{{ alias }}',
                '{{ path }}',
            ],
            [
                str($this->option('module'))->snake('.')->lower()->toString(),
                $this->option('module'),
            ],
            $stub
        );
    }
}
