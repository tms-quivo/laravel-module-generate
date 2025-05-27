<?php
namespace Tomosia\LaravelModuleGenerate\Generators\Commands;

use Illuminate\Foundation\Console\ObserverMakeCommand;
use Tomosia\LaravelModuleGenerate\Traits\ContainerCommandTrait;
use Tomosia\LaravelModuleGenerate\Traits\PrepareCommandTrait;
use Tomosia\LaravelModuleGenerate\Traits\PrepareModelTrait;

class ObserverGeneratorCommand extends ObserverMakeCommand
{
    use PrepareCommandTrait;
    use ContainerCommandTrait;
    use PrepareModelTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'module:make-observer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new observer class for provided container';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->prepareOptions();

        parent::handle();
    }
}
