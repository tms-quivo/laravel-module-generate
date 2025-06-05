<?php
namespace Tomosia\LaravelModuleGenerate\Traits;

use function Laravel\Prompts\text;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tomosia\LaravelModuleGenerate\Constants\ModuleLayer;

trait PromptsForMissingOptions
{
    /**
     * Prompt for missing options.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return void
     */
    protected function promptForMissingOptions(InputInterface $input, OutputInterface $output)
    {
		$constants = (new \ReflectionClass(ModuleLayer::class))->getConstants();

        foreach ($this->getDefinition()->getOptions() as $option) {
            if ($option->isValueRequired() && $input->getOption($option->getName()) === null && in_array($option->getName(), $constants)) {
                $value = text('Please enter the name of the ' . $option->getName(), required: true);

                if (empty(trim($value))) {
                    $this->error('You must enter a value for the ' . $option->getName() . ' option.');
                    exit(1);
                }

                $input->setOption($option->getName(), trim($value));
            }
        }
    }

    /**
     * Interact with the user.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return void
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (method_exists(parent::class, 'interact')) {
            parent::interact($input, $output);
        }

        $this->promptForMissingOptions($input, $output);
    }
}
