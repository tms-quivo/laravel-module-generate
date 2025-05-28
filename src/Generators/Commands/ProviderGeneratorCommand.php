<?php
namespace Tomosia\LaravelModuleGenerate\Generators\Commands;

use Illuminate\Foundation\Console\ProviderMakeCommand;
use Tomosia\LaravelModuleGenerate\Traits\ModuleCommandTrait;
use Tomosia\LaravelModuleGenerate\Traits\PrepareCommandTrait;

class ProviderGeneratorCommand extends ProviderMakeCommand
{
    use PrepareCommandTrait;
    use ModuleCommandTrait;

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
    protected $description = 'Generate a new provider class for provided container';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->prepareOptions();

        parent::handle();
    }

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
