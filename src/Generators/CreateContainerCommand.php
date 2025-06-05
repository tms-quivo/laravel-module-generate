<?php
namespace Tomosia\LaravelModuleGenerate\Generators;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class CreateContainerCommand extends Command implements PromptsForMissingInput
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

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * Create a new command instance.
     *
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     * @return void
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $containerName = $this->getContainerName();
        $containerPath = $this->getContainerPath($containerName);

        if ($this->containerExists($containerPath)) {
            return SymfonyCommand::FAILURE;
        }

        $this->createContainerStructure($containerPath);

        $this->components->info(sprintf('%s [%s] created successfully.', $this->type, ucfirst(str_replace('/', '\\', $containerPath))));
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
        if ($this->filesystem->exists($containerPath)) {
            $this->components->error("The container \"{$this->getContainerName()}\" already exists.");

            return true;
        }

        return false;
    }

    /**
     * Create the container directory structure.
     */
    protected function createContainerStructure(string $containerPath): void
    {
        $folders = config('module-generator.c_scaffold_folders', []);

        foreach ($folders as $structure) {
            $path = $containerPath . '/' . str_replace('\\', '/', $structure);
            $this->filesystem->makeDirectory($path, 0755, true, true);

            if (config('module-generator.stubs.gitkeep', false)) {
                $this->generateGitKeep($path);
            }
        }
    }

    /**
     * Generate git keep to the specified path.
     */
    public function generateGitKeep(string $path)
    {
        $this->filesystem->put($path.'/.gitkeep', '');
    }
}
