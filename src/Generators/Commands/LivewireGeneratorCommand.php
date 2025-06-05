<?php
namespace Tomosia\LaravelModuleGenerate\Generators\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Tomosia\LaravelModuleGenerate\Constants\ModuleLayer;
use Tomosia\LaravelModuleGenerate\Traits\ComponentParserTrait;
use Tomosia\LaravelModuleGenerate\Traits\ModuleCommandTrait;
use Tomosia\LaravelModuleGenerate\Traits\PromptsForMissingOptions;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

#[AsCommand(name: 'module:make-livewire', description: 'Generate a new livewire class for provided module')]
class LivewireGeneratorCommand extends Command implements PromptsForMissingInput
{
    use ModuleCommandTrait;
    use ComponentParserTrait;
    use PromptsForMissingOptions;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'module:make-livewire';

    /**
     * The type of the command.
     *
     * @var string
     */
    protected $type = 'Livewire';

    /**
     * The layer of class generated.
     *
     * @var string
     */
    protected string $layer = ModuleLayer::MODULE;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->parser();

        if (! $this->validateComponent()) {
            return SymfonyCommand::FAILURE;
        }

        $class = $this->makeClass();
        $view  = $this->createView();

        if ($class || $view) {
            $this->info('Livewire component created successfully');
            $class && $this->line("CLASS: " . $this->component->class->namespace);
            $view && $this->line("VIEW: " . $this->getViewSourcePath());
            $class && $this->line("TAG: " . $this->component->class->tag);

            return SymfonyCommand::SUCCESS;
        }

        return SymfonyCommand::FAILURE;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    public function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the Livewire component'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return array_merge(
            [
                ['module', null, InputOption::VALUE_REQUIRED, 'The name of the module'],
                ['force', null, InputOption::VALUE_NONE, 'Force the creation if file already exists'],
                ['inline', null, InputOption::VALUE_NONE, 'Create the view inline'],
                ['view', null, InputOption::VALUE_OPTIONAL, 'The name of the view'],
            ],
            parent::getOptions(),
        );
    }

    /**
     * Validate the component configuration.
     *
     * @return bool
     */
    private function validateComponent(): bool
    {
        if (! $this->checkClassNameValid()) {
            $this->error(sprintf('Class %s is not valid', $this->component->class->name));

            return false;
        }

        if ($this->checkReservedClassName()) {
            $this->error(sprintf('Class %s is reserved', $this->component->class->name));

            return false;
        }

        return true;
    }

    /**
     * Create the component class and view.
     *
     * @return bool
     */
    private function createComponent(): bool
    {
        return $this->makeClass() && ($this->isInline() || $this->createView());
    }

    protected function makeClass()
    {
        $classFile = $this->component->class->file;
        if (File::exists($classFile) && ! $this->isForce()) {
            $this->error(sprintf('Class %s already exists', $this->component->class->name));

            return false;
        }

        File::ensureDirectoryExists(dirname($classFile));
        File::put($classFile, $this->getClassContents());

        return $this->component->class;
    }

    protected function createView()
    {
        if ($this->isInline()) {
            return false;
        }

        $viewFile = $this->component->view->file;

        if (File::exists($viewFile) && ! $this->isForce()) {
            $this->error(sprintf('View %s already exists', $this->component->view->name));

            return false;
        }

        File::ensureDirectoryExists(dirname($viewFile));
        File::put($viewFile, $this->getViewContents());

        return $this->component->view;
    }
}
