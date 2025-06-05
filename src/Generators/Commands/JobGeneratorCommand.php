<?php
namespace Tomosia\LaravelModuleGenerate\Generators\Commands;

use Illuminate\Foundation\Console\JobMakeCommand;
use Tomosia\LaravelModuleGenerate\Constants\ModuleLayer;
use Tomosia\LaravelModuleGenerate\Traits\PrepareCommandTrait;

class JobGeneratorCommand extends JobMakeCommand
{
    use PrepareCommandTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'module:make-job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new job class for provided container';

    /**
     * The layer of class generated.
     *
     * @var string
     */
    protected string $layer = ModuleLayer::CONTAINER;
}
