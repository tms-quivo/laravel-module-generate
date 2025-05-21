<?php
namespace Tomosia\LaravelModuleGenerate\Generators\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Attribute\AsCommand;
use Tomosia\LaravelModuleGenerate\Traits\PrepareContainerCommandTrait;

#[AsCommand(name: 'module:make-model', description: 'Generate a new model class')]
class ModelGeneratorCommand extends GeneratorCommand
{
    use PrepareContainerCommandTrait;

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Model';

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
        $this->prepareOptions();
        $this->prepareModel();
        
        parent::handle();
        // Run Pint
        exec(base_path('vendor/bin/pint') . " {$this->filePath}");
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
