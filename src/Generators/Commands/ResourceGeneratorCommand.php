<?php
namespace Tomosia\LaravelModuleGenerate\Generators\Commands;

use Illuminate\Foundation\Console\ResourceMakeCommand;
use Tomosia\LaravelModuleGenerate\Constants\ModuleLayer;
use Tomosia\LaravelModuleGenerate\Traits\PrepareCommandTrait;

class ResourceGeneratorCommand extends ResourceMakeCommand
{
    use PrepareCommandTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'module:make-resource';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new resource class for provided module';

    /**
     * The layer of class generated.
     *
     * @var string
     */
    protected string $layer = ModuleLayer::MODULE;
}
