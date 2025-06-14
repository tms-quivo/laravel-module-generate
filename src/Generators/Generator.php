<?php
namespace Tomosia\LaravelModuleGenerate\Generators;

use Illuminate\Console\Command;
use Illuminate\Console\Concerns\CreatesMatchingTest;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

abstract class Generator extends Command
{
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected Filesystem $files;

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected string $type;

    /**
     * The reserved names that cannot be used.
     *
     * @var array
     */
    protected $reservedNames = [
        '__halt_compiler',
        'abstract',
        'and',
        'array',
        'as',
        'break',
        'callable',
        'case',
        'catch',
        'class',
        'clone',
        'const',
        'continue',
        'declare',
        'default',
        'die',
        'do',
        'echo',
        'else',
        'elseif',
        'empty',
        'enddeclare',
        'endfor',
        'endforeach',
        'endif',
        'endswitch',
        'endwhile',
        'enum',
        'eval',
        'exit',
        'extends',
        'false',
        'final',
        'finally',
        'fn',
        'for',
        'foreach',
        'function',
        'global',
        'goto',
        'if',
        'implements',
        'include',
        'include_once',
        'instanceof',
        'insteadof',
        'interface',
        'isset',
        'list',
        'match',
        'namespace',
        'new',
        'or',
        'print',
        'private',
        'protected',
        'public',
        'readonly',
        'require',
        'require_once',
        'return',
        'self',
        'static',
        'switch',
        'throw',
        'trait',
        'true',
        'try',
        'unset',
        'use',
        'var',
        'while',
        'xor',
        'yield',
        '__CLASS__',
        '__DIR__',
        '__FILE__',
        '__FUNCTION__',
        '__LINE__',
        '__METHOD__',
        '__NAMESPACE__',
        '__TRAIT__',
    ];

    /**
     * Create a new command instance.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        if (in_array(CreatesMatchingTest::class, class_uses_recursive($this))) {
            $this->addTestOptions();
        }

        $this->files = $files;
    }

    /**
     * Get the destination class path.
     *
     * @param string $name
     * @return string
     */
    protected function getPath(string $name): string
    {
        return base_path() . '/' . str_replace('\\', '/', $name) . '.php';
    }

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace(): string
    {
        return sprintf("%s\\", config('module-generator.module_namespace'));
    }

    /**
     * Get the snake case type.
     *
     * @return string
     */
    protected function getSnakeType(): string
    {
        return Str::snake($this->type);
    }

    /**
     * Get the config path by type.
     *
     * @return string
     * @throws \RuntimeException
     */
    protected function getConfigPathByType(): string
    {
        $path = config("module-generator.namespaces.{$this->getSnakeType()}");

        if (empty($path)) {
            throw new \RuntimeException("Path configuration for type '{$this->type}' not found");
        }

        return $path;
    }

    /**
     * Determine if the class already exists.
     *
     * @param string $path
     * @return bool
     */
    protected function alreadyExists(string $path): bool
    {
        return $this->files->exists($path);
    }

    /**
     * Make a directory.
     *
     * @param string $path
     * @return string
     */
    protected function makeDirectory(string $path): string
    {
        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }

        return $path;
    }

    /**
     * Replace the namespace for the given stub.
     *
     * @param string $stub
     * @param string $name
     * @return string
     */
    protected function replaceNamespace(string $stub, string $name): string
    {
        return $stub;
    }

    /**
     * Get the namespace for the given name.
     *
     * @param string $name
     * @return string
     */
    protected function getNameSpace(string $name): string
    {
        return trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     * @param string $stubPath
     * @return string
     */
    protected function buildClass(string $name, string $stubPath): string
    {
        $stub = $this->files->get($stubPath);

        return $this->replaceNamespace($stub, $name);
    }

    /**
     * Sort the imports for the given stub.
     *
     * @param string $stub
     * @return string
     */
    protected function sortImports(string $stub): string
    {
        if (preg_match('/(?P<imports>(?:^use [^;{]+;$\n?)+)/m', $stub, $match)) {
            $imports = explode("\n", trim($match['imports']));
            sort($imports);
            return str_replace(trim($match['imports']), implode("\n", $imports), $stub);
        }

        return $stub;
    }

    /**
     * Replace the general variables for the given stub.
     *
     * @param string $stub
     * @param string|null $subFolder
     * @return string
     */
    public function replaceGeneral(string $stub, ?string $subFolder = null): string
    {
        $type       = $this->getSnakeType();
        $pathConfig = $this->getConfigPathByType();
        $replace    = str($pathConfig)->append('\\' . $subFolder)->chopEnd('\\')->toString();

        return str_replace(
            [
                sprintf("{{ %s_path }}", $type),
            ],
            [
                $replace,
            ],
            $stub
        );
    }

    /**
     * Checks whether the given name is reserved.
     *
     * @param string $name
     * @return bool
     */
    protected function isReservedName(string $name): bool
    {
        $name = strtolower($name);

        return in_array($name, $this->reservedNames);
    }

    /**
     * Execute the console command.
     *
     * @param string $name
     * @param string $namespace
     * @param string $stubPath
     * @param string $replaceNamespaceFunc
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function generateFile(string $name, string $namespace, string $stubPath, string $replaceNamespaceFunc)
    {
        // First we need to ensure that the given name is not a reserved word within the PHP
        // language and that the class name will actually be valid. If it is not valid we
        // can error now and prevent from polluting the filesystem using invalid files.
        if ($this->isReservedName($name)) {
            $this->components->error('The name "' . $name . '" is reserved by PHP.');

            return false;
        }

        $name = $namespace . '\\' . $name;

        $path = $this->getPath($name);

        // Next, We will check to see if the class already exists. If it does, we don't want
        // to create the class and overwrite the user's code. So, we will bail out so the
        // code is untouched. Otherwise, we will continue generating this class' files.
        if ((! $this->hasOption('force') ||
            ! $this->option('force')) &&
            $this->alreadyExists($path)) {
            $this->error($this->type . ' already exists.');

            return false;
        }

        // Next, we will generate the path to the location where this class' file should get
        // written. Then, we will build the class and make the proper replacements on the
        // stub files so that it gets the correctly formatted namespace and class name.
        $this->makeDirectory($path);

        // Build class
        $stub = $this->files->get($stubPath);
        $this->files->put($path, $this->sortImports($this->{$replaceNamespaceFunc}($stub, $name)));

        // Format file with Pint
        exec(base_path('vendor/bin/pint') . " {$path}");

        $info = $this->type;

        $this->info(sprintf('%s [%s] created successfully.', $info, $path));
    }
}
