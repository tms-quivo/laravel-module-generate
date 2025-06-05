<?php
namespace Tomosia\LaravelModuleGenerate\Generators\Commands;

use Illuminate\Foundation\Console\ObserverMakeCommand;
use Tomosia\LaravelModuleGenerate\Constants\ModuleLayer;
use Tomosia\LaravelModuleGenerate\Traits\PrepareCommandTrait;
use Tomosia\LaravelModuleGenerate\Traits\PrepareModelTrait;

class ObserverGeneratorCommand extends ObserverMakeCommand
{
    use PrepareCommandTrait, PrepareModelTrait;

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
     * The layer of class generated.
     *
     * @var string
     */
    protected string $layer = ModuleLayer::CONTAINER;
}
