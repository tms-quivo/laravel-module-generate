<?php
namespace Tomosia\LaravelModuleGenerate\Generators\Commands;

use Illuminate\Console\GeneratorCommand;
use Tomosia\LaravelModuleGenerate\Traits\ContainerCommandTrait;
use Tomosia\LaravelModuleGenerate\Traits\PrepareCommandTrait;

class RepositoryGeneratorCommand extends GeneratorCommand
{
    use PrepareCommandTrait;
    use ContainerCommandTrait;

    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-repository {name} {--model= : The model class name} {--container= : The name of the container}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new repository class for provided container';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->prepareOptions();

        parent::handle();
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
