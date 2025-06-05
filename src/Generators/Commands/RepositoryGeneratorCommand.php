<?php
namespace Tomosia\LaravelModuleGenerate\Generators\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;
use Tomosia\LaravelModuleGenerate\Constants\ModuleLayer;
use Tomosia\LaravelModuleGenerate\Traits\PrepareCommandTrait;

#[AsCommand(name: 'module:make-repository', description: 'Generate a new repository class for provided container')]
class RepositoryGeneratorCommand extends GeneratorCommand
{
    use PrepareCommandTrait;

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository';

    /**
     * The layer of class generated.
     *
     * @var string
     */
    protected string $layer = ModuleLayer::CONTAINER;

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return array_merge(
            [
                ['model', null, InputOption::VALUE_OPTIONAL, 'The model class name'],
                ['container', null, InputOption::VALUE_REQUIRED, 'The name of the container'],
            ],
            parent::getOptions(),
        );
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     * @return string
     */
    protected function buildClass($name)
    {
        if (! $this->option('model')) {
            $this->type = 'Repository.plain';
        }

        $stub = parent::buildClass($name);

        return $this->replaceStub($stub);
    }

    /**
     * Replace the stub variables for the generator.
     *
     * @param string $stub
     * @return string
     */
    protected function replaceStub(string $stub): string
    {
        $model          = $this->option('model');
        $container      = $this->option('container');
        $modelNamespace = $this->rootNamespace() . $container . '\\Models\\' . $model;

        return str_replace(
            [
                '{{ model }}',
                '{{ modelNamespace }}',
            ],
            [
                class_basename($this->option('model')),
                $modelNamespace,
            ],
            $stub
        );
    }
}
