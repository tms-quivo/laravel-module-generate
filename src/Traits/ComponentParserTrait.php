<?php
namespace Tomosia\LaravelModuleGenerate\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Features\SupportConsoleCommands\Commands\MakeCommand;
use stdClass;

trait ComponentParserTrait
{
    use CommandHelper;

    protected ?stdClass $component = null;
    protected ?string $module = null;
    protected ?Collection $directories = null;

    /**
     * Parse the component name and return the component object.
     *
     * @return self
     */
    protected function parser(): self
    {
        $this->module = $this->getModule();
        $this->directories = collect(preg_split('/[.\/(\\\\)]+/', $this->argument('name')))
            ->map(fn($part) => Str::studly($part));
        $this->component = $this->getComponent();

        return $this;
    }

    /**
     * Get the component object.
     *
     * @return stdClass
     */
    protected function getComponent(): stdClass
    {
        return (object) [
            'class' => $this->getClassInfo(),
            'view'  => $this->getViewInfo(),
            'stub'  => $this->getStubInfo(),
        ];
    }

    /**
     * Get the class information.
     *
     * @return stdClass
     */
    protected function getClassInfo(): stdClass
    {
        $modulePath = $this->getModulePath();
        $moduleLivewireNamespace = $this->getModuleLivewireNamespace();
        $classDir = Str::of($modulePath)
            ->append('/' . $moduleLivewireNamespace)
            ->replace('\\', '/')
            ->toString();
        $classPath = $this->directories->implode('/');
        $className = $this->directories->last();

        return (object) [
            'dir'       => $classDir,
            'path'      => $classPath,
            'file'      => "{$classDir}/{$className}.php",
            'namespace' => $this->getNamespace($classPath),
            'name'      => $className,
            'tag'       => $this->getComponentTag(),
        ];
    }

    /**
     * Get the view information.
     *
     * @return stdClass
     */
    protected function getViewInfo(): stdClass
    {
        $moduleLivewireViewDir = $this->getModuleLivewireViewDir();
        $path = $this->option('view')
            ? strtr($this->option('view'), ['.' => '/'])
            : $this->directories->map(fn($part) => Str::kebab($part))->implode('/');

        return (object) [
            'dir'    => $moduleLivewireViewDir,
            'path'   => $path,
            'folder' => Str::after($moduleLivewireViewDir, 'views/'),
            'file'   => "{$moduleLivewireViewDir}/{$path}.blade.php",
            'name'   => strtr($path, ['/' => '.']),
        ];
    }

    /**
     * Get the stub information.
     *
     * @return stdClass
     */
    protected function getStubInfo(): stdClass
    {
        $stubDir = __DIR__ . '/../Generators/stubs/';
        $baseStubName = str($this->type)->snake('-')->lower()->toString();
        $classStubName = $this->isInline()
            ? "{$baseStubName}.inline.stub"
            : "{$baseStubName}.stub";

        return (object) [
            'dir'   => $stubDir,
            'class' => $stubDir . $classStubName,
            'view'  => $stubDir . 'livewire.view.stub',
        ];
    }

    /**
     * Get the component tag.
     *
     * @return string
     */
    protected function getComponentTag(): string
    {
        $directoryAsView = $this->directories
            ->map(fn($part) => Str::kebab($part))
            ->implode('.');

        return Str::replaceLast(
            '.index',
            '',
            "<livewire:{$this->getModuleLowerName()}::{$directoryAsView} />"
        );
    }

    /**
     * Get the class contents.
     *
     * @return string
     */
    protected function getClassContents(): string
    {
        $template = file_get_contents($this->component->stub->class);

        if ($this->isInline()) {
            $template = str_replace('{{ quote }}', $this->getComponentQuote(), $template);
        }

        return str_replace(
            ['{{ namespace }}', '{{ class }}', '{{ view }}'],
            [$this->getComponentNamespace(), $this->getClassName(), $this->getViewName()],
            $template
        );
    }

    /**
     * Get the component namespace.
     *
     * @return string
     */
    protected function getComponentNamespace(): string
    {
        return $this->component->class->namespace;
    }

    /**
     * Get the class name.
     *
     * @return string
     */
    protected function getClassName(): string
    {
        return $this->component->class->name;
    }

    /**
     * Get the view name.
     *
     * @return string
     */
    protected function getViewName(): string
    {
        return sprintf(
            '%s::%s.%s',
            $this->getModuleLowerName(),
            $this->component->view->folder,
            $this->component->view->name
        );
    }

    /**
     * Get the view source path.
     *
     * @return string
     */
    protected function getViewSourcePath(): string
    {
        return Str::of($this->component->view->file)
            ->after($this->getBasePath().'/')
            ->replace('//', '/')
            ->toString();
    }

    /**
     * Get the base path.
     *
     * @param string|null $path
     * @return string
     */
    protected function getBasePath($path = null): string
    {
        return strtr(base_path($path), ['\\' => '/']);
    }

    /**
     * Get the component quote.
     *
     * @return string
     */
    protected function getComponentQuote(): string
    {
        return sprintf(
            'The <code>%s</code> livewire component is loaded from the <code>%s</code> module.',
            $this->getClassName(),
            $this->getModule()
        );
    }

    /**
     * Get the view contents.
     *
     * @return string
     */
    protected function getViewContents(): string
    {
        return str_replace(
            '{{ quote }}',
            $this->getComponentQuote(),
            file_get_contents($this->component->stub->view)
        );
    }

    /**
     * Check if the class name is valid.
     *
     * @return bool
     */
    protected function checkClassNameValid()
    {
        return $this->isClassNameValid($this->argument('name'));
    }

    /**
     * Check if the class name is reserved.
     *
     * @return bool
     */
    protected function checkReservedClassName()
    {
        return $this->isReservedClassName($this->argument('name'));
    }

    /**
     * Check if the class name is valid.
     *
     * @param string $name
     * @return bool
     */
    protected function isClassNameValid($name)
    {
        return (new MakeCommand())->isClassNameValid($name);
    }

    /**
     * Check if the class name is reserved.
     *
     * @param string $name
     * @return bool
     */
    protected function isReservedClassName($name)
    {
        return (new MakeCommand())->isReservedClassName($name);
    }
}
