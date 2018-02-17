<?php

namespace Laravel\Flow\Console;

use Illuminate\Console\GeneratorCommand;

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
        return __DIR__.'/stubs/flow.stub';
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
}
