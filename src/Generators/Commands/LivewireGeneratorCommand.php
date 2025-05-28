<?php
namespace App\Console\Commands\Generators;

use Tomosia\LaravelModuleGenerate\Traits\ComponentParserTrait;
use Tomosia\LaravelModuleGenerate\Traits\ModuleCommandTrait;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Facades\File;

class LivewireGeneratorCommand extends Command implements PromptsForMissingInput
{
    use ModuleCommandTrait;
    use ComponentParserTrait;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'module:make-livewire {name} {--module= : The name of the module} {--force} {--inline} {--view=}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Generate a new livewire class for provided module';

    /**
     * The type of the command.
     *
     * @var string
     */
    protected $type = 'Livewire';

    /**
     * Execute the console command.
     *
     * @return bool
     */
    public function handle()
    {
        $this->prepareOptions();
        $this->parser();

        if (! $this->validateComponent()) {
            return false;
        }

        $class = $this->makeClass();
        $view  = $this->createView();

        if ($class || $view) {
            $this->info('Livewire component created successfully');
            $class && $this->line("CLASS: " . $this->component->class->namespace);
            $view && $this->line("VIEW: " . $this->getViewSourcePath());
            $class && $this->line("TAG: " . $this->component->class->tag);

            return true;
        }

        return false;
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
