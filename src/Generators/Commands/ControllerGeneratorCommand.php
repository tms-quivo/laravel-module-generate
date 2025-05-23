<?php
namespace Tomosia\LaravelModuleGenerate\Generators\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Tomosia\LaravelModuleGenerate\Traits\PrepareCommandTrait;

#[AsCommand(name: 'module:make-controller', description: 'Generate a new controller class')]
class ControllerGeneratorCommand extends GeneratorCommand
{
    use PrepareCommandTrait;

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

    public function handle()
    {
        $this->prepareOptions();

        parent::handle();
    }
}
