<?php

namespace Tomosia\LaravelModuleGenerate\Generators\Commands;

use Symfony\Component\Console\Command\Command;
use Tomosia\LaravelModuleGenerate\Generators\Generator;
use Tomosia\LaravelModuleGenerate\Traits\PrepareCommandTrait;

class ResourceGeneratorCommand extends Generator
{
	use PrepareCommandTrait;

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'module:make-resource {name} {--module= : The name of the module} {--collection}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a new resource';

	/**
	 * The type of class being generated.
	 *
	 * @var string
	 */
	protected string $type = 'Resource';

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		if (! $this->option('module')) {
            $this->error('The --module option is required.');

            return Command::FAILURE;
        }

		if ($this->option('collection')) {
			$this->type = "{$this->type}Collection";
		}
  
		$this->generateFile(
            $this->getClassName(),
            $this->getClassNamespace(),
            $this->getStub(),
            'replaceStub'
        );
	}
}
