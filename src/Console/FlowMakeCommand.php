<?php

namespace Laravel\Flow\Console;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class FlowMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:flow';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new flow class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Flow';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->option('interval-delay')) {
            return __DIR__ . '/stubs/flow-interval-delay.stub';
        } else if ($this->option('delay')) {
            return __DIR__ . '/stubs/flow-delay.stub';
        }

        return __DIR__ . '/stubs/flow.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Flows';
    }

    protected function getOptions()
    {
        return [
            ['delay', null, InputOption::VALUE_NONE, 'Run the flow after a specified delay'],
            ['interval-delay', null, InputOption::VALUE_NONE, 'Run the flow after a specified delay checking with an interval'],
        ];
    }
}
