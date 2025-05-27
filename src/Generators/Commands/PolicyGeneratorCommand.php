<?php
namespace Tomosia\LaravelModuleGenerate\Generators\Commands;

use Illuminate\Foundation\Console\PolicyMakeCommand;
use Tomosia\LaravelModuleGenerate\Traits\ContainerCommandTrait;
use Tomosia\LaravelModuleGenerate\Traits\PrepareCommandTrait;
use Tomosia\LaravelModuleGenerate\Traits\PrepareModelTrait;

class PolicyGeneratorCommand extends PolicyMakeCommand
{
    use PrepareCommandTrait;
    use ContainerCommandTrait;
    use PrepareModelTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'module:make-policy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new policy class for provided container';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->prepareOptions();

        parent::handle();
    }
}
