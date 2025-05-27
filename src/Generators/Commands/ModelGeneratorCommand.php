<?php
namespace Tomosia\LaravelModuleGenerate\Generators\Commands;

use Illuminate\Foundation\Console\ModelMakeCommand;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Symfony\Component\Console\Command\Command;
use Tomosia\LaravelModuleGenerate\Traits\ContainerCommandTrait;
use Tomosia\LaravelModuleGenerate\Traits\PrepareCommandTrait;

class ModelGeneratorCommand extends ModelMakeCommand
{
    use PrepareCommandTrait;
    use ContainerCommandTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'module:make-model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new model class for provided container';

    /**
     * The name of the table.
     *
     * @var string
     */
    protected string $table = '';

    /**
     * Default excluded columns from fillable.
     */
    protected array $excludedColumns = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->prepareOptions();
            $this->prepareModel();

            parent::handle();

            // Run Pint for code formatting
            $this->formatCodeWithPint();
        } catch (\Exception $e) {
            $this->error("Error generating model: {$e->getMessage()}");

            return Command::FAILURE;
        }
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = parent::buildClass($name);

        return $this->replaceStub($stub);
    }

    /**
     * Prepare the model.
     */
    protected function prepareModel()
    {
        $this->table = $this->option('table')
        ?: Str::of(class_basename($this->argument('name')))
            ->snake()
            ->plural()
            ->toString();

        return $this;
    }

    /**
     * Get the table name.
     *
     * @return string|null
     */
    protected function getTableName(): ?string
    {
        return $this->option('table')
            ? sprintf("    protected \$table = '%s';", $this->option('table'))
            : null;
    }

    /**
     * Replace the stub variables for the generator.
     *
     * @param string $stub
     * @return string
     */
    protected function replaceStub(string $stub): string
    {
        return str_replace(
            [
                '{{ table }}',
                '{{ fillable }}',
            ],
            [
                $this->getTableName(),
                $this->getFillableFromMigration($this->table),
            ],
            $stub
        );
    }

    /**
     * Get the fillable from migration.
     *
     * @param string $name
     * @return string
     */
    protected function getFillableFromMigration(string $name): string
    {
        if (! Schema::hasTable($name)) {
            return '';
        }

        $columns = Schema::getColumnListing($name);

        return collect($columns)
            ->diff($this->excludedColumns)
            ->map(fn($col) => "        '{$col}',")
            ->implode("\n");
    }

    protected function createController()
    {
        $controller = Str::of(class_basename($this->argument('name')))
            ->studly()
            ->append('Controller')
            ->toString();
        $modelName         = $this->qualifyClass($this->getNameInput());
        $shouldCreateModel = $this->option('resource') || $this->option('api');

        $this->call('module:make-controller', [
            'name'       => $controller,
            '--model'    => $shouldCreateModel ? $modelName : null,
            '--module'   => $this->option('module'),
            '--api'      => $this->option('api'),
            '--requests' => $this->option('requests') || $this->option('all'),
        ]);
    }

    protected function createPolicy()
    {
        $policy = Str::of(class_basename($this->argument('name')))
            ->studly()
            ->append('Policy')
            ->toString();

        $this->call('module:make-policy', [
            'name'        => $policy,
            '--model'     => $this->qualifyClass($this->getNameInput()),
            '--container' => $this->option('container'),
        ]);
    }
}
