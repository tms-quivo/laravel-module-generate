<?php
namespace Tomosia\LaravelModuleGenerate\Generators\Commands;

use Illuminate\Foundation\Console\MailMakeCommand;
use Tomosia\LaravelModuleGenerate\Constants\ModuleLayer;
use Tomosia\LaravelModuleGenerate\Traits\PrepareCommandTrait;

class MailGeneratorCommand extends MailMakeCommand
{
    use PrepareCommandTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'module:make-mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new mail class for provided container';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Mail';

    /**
     * The layer of class generated.
     *
     * @var string
     */
    protected string $layer = ModuleLayer::CONTAINER;
}
