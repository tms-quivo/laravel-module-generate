<?php
namespace Tomosia\LaravelModuleGenerate\Generators\Commands;

use Illuminate\Foundation\Console\NotificationMakeCommand;
use Tomosia\LaravelModuleGenerate\Traits\ContainerCommandTrait;
use Tomosia\LaravelModuleGenerate\Traits\PrepareCommandTrait;

class NotificationGeneratorCommand extends NotificationMakeCommand
{
    use PrepareCommandTrait;
    use ContainerCommandTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'module:make-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new notification class for provided container';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->prepareOptions();

        parent::handle();
    }
}
