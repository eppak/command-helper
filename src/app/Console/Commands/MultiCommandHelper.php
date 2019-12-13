<?php


namespace App\Console\Commands;

use ReflectionClass;
use ReflectionException;

trait MultiCommandHelper
{

    /**
     * @var string
     */
    private $command = 'cmd';

    /**
     * @var array
     */
    private $commands = [];

    /**
     *
     */
    private function logo() {
        $this->info("MyLogo");
    }

    /**
     * @param $commands
     */
    private function setCommand($command, $commands)
    {
        $this->command = $command;
        $this->commands = $commands;
    }

    /**
     *
     */
    private function help()
    {
        $help = [];
        foreach (collect($this->commands)->keys()->sort() as $key) {
            $help[] = ['name' => $key, 'help' => $this->commands[$key]['help']];
        }

        $this->logo();
        $this->table(['Name', 'Help'], $help);
    }

    /**
     * @return bool
     */
    private function isValid() {
        $command = $this->argument($this->command);

        if (!array_key_exists($command, $this->commands)) {
            $this->warn("UNKNOWN COMMAND {$command}");
            $this->help();
            return false;
        }

        return true;
    }

    /**
     * @throws ReflectionException
     */
    private function exeCommand() {
        $command = $this->argument($this->command);
        $functions = $this->commands[$command]['functions'];

        foreach (explode('|', $functions) as $function) {
            $this->invoke($function);
        }
    }

    /**
     * @param $function
     * @throws ReflectionException
     */
    private function invoke($function) {
        $class = new ReflectionClass(get_class ($this));
        $method = $class->getMethod($function);
        $method->setAccessible(true);
        $method->invoke($this);
    }
}
