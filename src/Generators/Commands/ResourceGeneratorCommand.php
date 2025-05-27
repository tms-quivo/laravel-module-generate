<?php
namespace Tomosia\LaravelModuleGenerate\Generators\Commands;

use Illuminate\Foundation\Console\ResourceMakeCommand;
use Tomosia\LaravelModuleGenerate\Traits\ModuleCommandTrait;
use Tomosia\LaravelModuleGenerate\Traits\PrepareCommandTrait;

class ResourceGeneratorCommand extends ResourceMakeCommand
{
    use PrepareCommandTrait;
    use ModuleCommandTrait;

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
     * Execute the console command.
     */
    public function handle()
    {
        $this->prepareOptions();

        parent::handle();
    }
}
