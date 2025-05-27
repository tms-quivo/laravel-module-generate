<?php
namespace Tomosia\LaravelModuleGenerate\Generators\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Tomosia\LaravelModuleGenerate\Traits\ContainerCommandTrait;
use Tomosia\LaravelModuleGenerate\Traits\PrepareCommandTrait;

#[AsCommand(name: 'module:make-action', description: 'Generate a new action class')]
class ActionGeneratorCommand extends GeneratorCommand
{
    use PrepareCommandTrait;
    use ContainerCommandTrait;

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Action';

    public function handle()
    {
        $this->prepareOptions();

        parent::handle();
    }
}
