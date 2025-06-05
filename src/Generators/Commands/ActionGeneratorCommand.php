<?php
namespace Tomosia\LaravelModuleGenerate\Generators\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Tomosia\LaravelModuleGenerate\Constants\ModuleLayer;
use Tomosia\LaravelModuleGenerate\Traits\PrepareCommandTrait;

#[AsCommand(name: 'module:make-action', description: 'Generate a new action class')]
class ActionGeneratorCommand extends GeneratorCommand
{
    use PrepareCommandTrait;

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Action';

    /**
     * The layer of class generated.
     *
     * @var string
     */
    protected string $layer = ModuleLayer::CONTAINER;

    protected function qualifyClass($name)
    {
        return parent::qualifyClass($name);
    }
}
