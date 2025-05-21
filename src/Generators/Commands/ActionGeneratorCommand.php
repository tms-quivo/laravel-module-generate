<?php
namespace Tomosia\LaravelModuleGenerate\Generators\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Tomosia\LaravelModuleGenerate\Traits\PrepareContainerCommandTrait;

#[AsCommand(name: 'module:make-action', description: 'Generate a new action class')]
class ActionGeneratorCommand extends GeneratorCommand
{
    use PrepareContainerCommandTrait;

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
