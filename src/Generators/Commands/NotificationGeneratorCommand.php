<?php
namespace Tomosia\LaravelModuleGenerate\Generators\Commands;

use Illuminate\Foundation\Console\NotificationMakeCommand;
use Tomosia\LaravelModuleGenerate\Constants\ModuleLayer;
use Tomosia\LaravelModuleGenerate\Traits\PrepareCommandTrait;

class NotificationGeneratorCommand extends NotificationMakeCommand
{
    use PrepareCommandTrait;

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
     * The layer of class generated.
     *
     * @var string
     */
    protected string $layer = ModuleLayer::CONTAINER;
}
