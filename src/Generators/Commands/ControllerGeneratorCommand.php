<?php
namespace Tomosia\LaravelModuleGenerate\Generators\Commands;

use Symfony\Component\Console\Command\Command;
use Tomosia\LaravelModuleGenerate\Generators\Generator;
use Tomosia\LaravelModuleGenerate\Traits\PrepareCommandTrait;

class ControllerGeneratorCommand extends Generator
{
    use PrepareCommandTrait;

    protected $signature = 'm:make:controller {name} {--module= : The name of the module}';

    protected $description = 'Create a new controller class';

    protected string $type = 'Controller';

    public function handle()
    {
        if (! $this->option('module')) {
            $this->error('The --module option is required.');

            return Command::FAILURE;
        }

        $this->generateFile(
            $this->getClassName(),
            $this->getClassNamespace(),
            $this->getStub(),
            'replaceStub'
        );
    }
}
