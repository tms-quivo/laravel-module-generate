<?php
namespace Tomosia\LaravelModuleGenerate\Generators;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CreateContainerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'container:create {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new container';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Container';

    public function handle()
    {
        $containerName = $this->getContainerName();
        $containerPath = $this->getContainerPath($containerName);

        if ($this->containerExists($containerPath)) {
            return false;
        }

        $this->createContainerStructure($containerPath);

        $namespace = config('module-generator.container_namespace') . '\\' . $containerName;
        $this->components->info(sprintf('%s [%s] created successfully.', $this->type, $namespace));

        return true;
    }

    /**
     * Get the container name from input.
     */
    protected function getContainerName(): string
    {
        return Str::of($this->argument('name'))
            ->trim()
            ->toString();
    }

    /**
     * Get the full container path.
     */
    protected function getContainerPath(string $containerName): string
    {
        return config('module-generator.container_path') . DIRECTORY_SEPARATOR . $containerName;
    }

    /**
     * Check if container already exists.
     */
    protected function containerExists(string $containerPath): bool
    {
        if (File::exists($containerPath)) {
            $this->components->error("The container \"{$this->getContainerName()}\" already exists.");
            
            return true;
        }

        return false;
    }

    /**
     * Create the container directory structure.
     */
    protected function createContainerStructure(string $path): void
    {
        $folders = config('module-generator.c_scaffold_folders', []);

        foreach ($folders as $structure) {
            File::makeDirectory($path . '/' . str_replace('\\', '/', $structure), 0755, true, true);
        }
    }
}
