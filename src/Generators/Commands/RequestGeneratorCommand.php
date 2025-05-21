<?php
namespace Tomosia\LaravelModuleGenerate\Generators\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Tomosia\LaravelModuleGenerate\Traits\PrepareCommandTrait;

#[AsCommand(name: 'module:make-request', description: 'Generate a new request class')]
class RequestGeneratorCommand extends GeneratorCommand
{
    use PrepareCommandTrait;

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Request';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->prepareOptions();

        parent::handle();
    }
}
