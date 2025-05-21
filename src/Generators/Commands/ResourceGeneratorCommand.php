<?php
namespace Tomosia\LaravelModuleGenerate\Generators\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Tomosia\LaravelModuleGenerate\Traits\PrepareCommandTrait;

#[AsCommand(name: 'module:make-resource', description: 'Generate a new resource class')]
class ResourceGeneratorCommand extends GeneratorCommand
{
    use PrepareCommandTrait;

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Resource';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->prepareOptions();
        $this->prepareResource();

        parent::handle();
    }

    /**
     * Prepare options.
     *
     * @return static
     */
    protected function prepareResource()
    {
        if ($this->option('collection')) {
            $this->type = "{$this->type} collection";
        }

        return $this;
    }
}
