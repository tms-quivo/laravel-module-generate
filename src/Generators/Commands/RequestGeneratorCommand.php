<?php
namespace Tomosia\LaravelModuleGenerate\Generators\Commands;

use Illuminate\Foundation\Console\RequestMakeCommand;
use Tomosia\LaravelModuleGenerate\Constants\ModuleLayer;
use Tomosia\LaravelModuleGenerate\Traits\PrepareCommandTrait;

class RequestGeneratorCommand extends RequestMakeCommand
{
    use PrepareCommandTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'module:make-request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new request class for provided module';

    /**
     * The layer of class generated.
     *
     * @var string
     */
    protected string $layer = ModuleLayer::MODULE;
}
