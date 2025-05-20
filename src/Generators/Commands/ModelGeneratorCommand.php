<?php
namespace Tomosia\LaravelModuleGenerate\Generators\Commands;

use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Command\Command;
use Tomosia\LaravelModuleGenerate\Generators\Generator;
use Tomosia\LaravelModuleGenerate\Traits\PrepareContainerCommandTrait;

class ModelGeneratorCommand extends Generator
{
    use PrepareContainerCommandTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-model {name} {--container= : The name of the container} {--table= : The name of the table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected string $type = 'Model';

    /**
     * The name of the table.
     *
     * @var string
     */
    protected string $table = '';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (! $this->option('container')) {
            $this->error('The --container option is required.');

            return Command::FAILURE;
        }
        $this->prepareModel();

        $this->generateFile(
            $this->getClassName(),
            $this->getClassNamespace(),
            $this->getStub(),
            'replaceStub'
        );
    }

    /**
     * Prepare the model.
     */
    protected function prepareModel()
    {
        if ($this->option('table')) {
            $this->table = $this->option('table');

            return $this;
        }

        $this->table = str($this->argument('name'))->snake()->plural()->toString();

        return $this;
    }

    /**
     * Get the table name.
     *
     * @return string|null
     */
    protected function getTableName(): ?string
    {
        if ($this->option('table')) {
            return sprintf("    protected \$table = '%s';", $this->option('table'));
        }

        return null;
    }

    /**
     * Replace the stub variables for the generator.
     *
     * @param string $stub
     * @return string
     */
    protected function replaceStub(string $stub): string
    {
        $stub = $this->replaceGeneral($stub, $this->getSubNamespace());

        return str_replace(
            [
                '{{ container }}',
                '{{ name }}',
                '{{ table }}',
                '{{ fillable }}',
            ],
            [
                $this->option('container'),
                $this->getClassName(),
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
        $columns = Schema::getColumnListing($name);

        return collect($columns)
            ->diff([
                'id',
                'created_at',
                'updated_at',
                'deleted_at',
            ])
            ->map(fn($col) => "        '{$col}',")
            ->implode("\n");
    }
}
