<?php

namespace Tomosia\LaravelModuleGenerate\Generators\Packages;

use Spatie\LaravelData\Commands\DataMakeCommand;
use Tomosia\LaravelModuleGenerate\Constants\ModuleLayer;
use Tomosia\LaravelModuleGenerate\Traits\PrepareCommandTrait;

class CreateDataCommand extends DataMakeCommand
{
	use PrepareCommandTrait;

	/**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'module:make-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new data class for provided container';

	/**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Data';

    /**
     * The layer of class generated.
     *
     * @var string
     */
    protected string $layer = ModuleLayer::CONTAINER;
}
