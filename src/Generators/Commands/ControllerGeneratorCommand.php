<?php
namespace Tomosia\LaravelModuleGenerate\Generators\Commands;

use function Laravel\Prompts\text;
use Illuminate\Routing\Console\ControllerMakeCommand;
use Illuminate\Support\Str;
use Tomosia\LaravelModuleGenerate\Traits\ModuleCommandTrait;
use Tomosia\LaravelModuleGenerate\Traits\PrepareCommandTrait;
use Tomosia\LaravelModuleGenerate\Traits\PrepareModelTrait;

class ControllerGeneratorCommand extends ControllerMakeCommand
{
    use PrepareCommandTrait;
    use ModuleCommandTrait;
    use PrepareModelTrait;

    private const DEFAULT_NAMESPACE     = 'Illuminate\\Http';
    private const DEFAULT_REQUEST_CLASS = 'Request';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'module:make-controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new controller class for provided module';

    public function handle()
    {
        $this->prepareOptions();

        parent::handle();
    }

    protected function getStub(): string
    {
        return parent::getStub();
    }

    protected function buildFormRequestReplacements(array $replace, $modelClass)
    {
        $namespace          = self::DEFAULT_NAMESPACE;
        $storeRequestClass  = self::DEFAULT_REQUEST_CLASS;
        $updateRequestClass = self::DEFAULT_REQUEST_CLASS;

        if ($this->option('requests')) {
            $moduleNamespace = sprintf('%s%s', $this->rootNamespace(), $this->option('module'));
            $namespace       = sprintf('%s\\Http\\Requests', $moduleNamespace);

            [$storeRequestClass, $updateRequestClass] = $this->generateFormRequests(
                $modelClass,
                $storeRequestClass,
                $updateRequestClass
            );
        }

        $namespacedRequests = $this->buildNamespacedRequests($namespace, $storeRequestClass, $updateRequestClass);

        return array_merge($replace, [
            '{{ storeRequest }}'            => $storeRequestClass,
            '{{storeRequest}}'              => $storeRequestClass,
            '{{ updateRequest }}'           => $updateRequestClass,
            '{{updateRequest}}'             => $updateRequestClass,
            '{{ namespacedStoreRequest }}'  => $namespace . '\\' . $storeRequestClass,
            '{{namespacedStoreRequest}}'    => $namespace . '\\' . $storeRequestClass,
            '{{ namespacedUpdateRequest }}' => $namespace . '\\' . $updateRequestClass,
            '{{namespacedUpdateRequest}}'   => $namespace . '\\' . $updateRequestClass,
            '{{ namespacedRequests }}'      => $namespacedRequests,
            '{{namespacedRequests}}'        => $namespacedRequests,
        ]);
    }

    /**
     * Generate the form requests for the given model and classes.
     *
     * @param  string  $modelClass
     * @param  string  $storeRequestClass
     * @param  string  $updateRequestClass
     * @return array
     */
    protected function generateFormRequests($modelClass, $storeRequestClass, $updateRequestClass)
    {
        $storeRequestClass = 'Store' . class_basename($modelClass) . 'Request';

        $this->call('module:make-request', [
            'name'     => $storeRequestClass,
            '--module' => $this->option('module'),
        ]);

        $updateRequestClass = 'Update' . class_basename($modelClass) . 'Request';

        $this->call('module:make-request', [
            'name'     => $updateRequestClass,
            '--module' => $this->option('module'),
        ]);

        return [$storeRequestClass, $updateRequestClass];
    }

    /**
     * Build the namespaced requests string.
     *
     * @param string $namespace
     * @param string $storeRequestClass
     * @param string $updateRequestClass
     * @return string
     */
    private function buildNamespacedRequests(string $namespace, string $storeRequestClass, string $updateRequestClass): string
    {
        $namespacedRequests = $namespace . '\\' . $storeRequestClass . ';';

        if ($storeRequestClass !== $updateRequestClass) {
            $namespacedRequests .= PHP_EOL . 'use ' . $namespace . '\\' . $updateRequestClass . ';';
        }

        return $namespacedRequests;
    }

    protected function qualifyModel(string $model): string
    {
        if (! $this->option('container')) {
            $this->input->setOption('container', text('Please enter the name of the container', required: true));
        }

        $container     = $this->option('container');
        $rootNamespace = config('module-generator.container_namespace') . '\\' . $container . '\\Models\\';
        $model         = $this->normalizeModelName($model);

        if (Str::startsWith($model, $rootNamespace)) {
            return $model;
        }

        return $this->buildModelPath($rootNamespace, $model);
    }
}
