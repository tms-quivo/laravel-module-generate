<?php
namespace Tomosia\LaravelModuleGenerate\Generators\Commands;

use Illuminate\Foundation\Console\ListenerMakeCommand;
use Illuminate\Support\Str;
use Tomosia\LaravelModuleGenerate\Traits\ContainerCommandTrait;
use Tomosia\LaravelModuleGenerate\Traits\PrepareCommandTrait;

class ListenerGeneratorCommand extends ListenerMakeCommand
{
    use PrepareCommandTrait;
    use ContainerCommandTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'module:make-listener';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new listener class for provided container';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->prepareOptions();

        parent::handle();
    }

    protected function buildClass($name)
    {
        $event     = $this->option('event');
        $container = $this->option('container');
        $namespace = $this->getRootNamespace($container);

        $event = $this->resolveEventNamespace($event, $namespace);

        $stub = $this->parentbuildClass($name);

        return $this->replaceEventPlaceholders($stub, $event);
    }

    /**
     * Resolve the event namespace.
     *
     * @param string $event
     * @param string $namespace
     * @return string
     */
    private function resolveEventNamespace(string $event, string $namespace): string
    {
        if (! Str::startsWith($event, [
            $this->laravel->getNamespace(),
            'Illuminate',
            '\\',
        ])) {
            return $namespace . 'Events\\' . str_replace('/', '\\', $event);
        }

        return $event;
    }

    /**
     * Replace event placeholders in the stub.
     *
     * @param string $stub
     * @param string $event
     * @return string
     */
    private function replaceEventPlaceholders(string $stub, string $event): string
    {
        $stub = str_replace(
            ['DummyEvent', '{{ event }}'],
            class_basename($event),
            $stub
        );

        return str_replace(
            ['DummyFullEvent', '{{ eventNamespace }}'],
            trim($event, '\\'),
            $stub
        );
    }

    /**
     * Get the root namespace based on container.
     *
     * @param string|null $container
     * @return string
     */
    private function getRootNamespace(?string $container): string
    {
        if ($container === null) {
            return $this->laravel->getNamespace();
        }

        return $this->rootNamespace() . $container . '\\';
    }

    /**
     * Build the class using the parent implementation.
     *
     * @param string $name
     * @return string
     */
    protected function parentbuildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }
}
