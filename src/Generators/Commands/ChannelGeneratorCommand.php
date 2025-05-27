<?php
namespace Tomosia\LaravelModuleGenerate\Generators\Commands;

use Illuminate\Foundation\Console\ChannelMakeCommand;
use Tomosia\LaravelModuleGenerate\Traits\ContainerCommandTrait;
use Tomosia\LaravelModuleGenerate\Traits\PrepareCommandTrait;

class ChannelGeneratorCommand extends ChannelMakeCommand
{
    use PrepareCommandTrait;
    use ContainerCommandTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'module:make-channel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new channel class for provided container';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->prepareOptions();

        parent::handle();
    }
}
