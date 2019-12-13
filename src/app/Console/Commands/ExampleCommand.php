<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExampleCommand extends Command
{
    use MultiCommandHelper;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'example:command {cmd} {option?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Example Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->setCommand(
            'cmd',
            [
                'command1' => ['functions' => 'command1Function', 'help' => 'Execute command 1' ],
                'command2' => ['functions' => 'command1Function|command2Function', 'help' => 'Execute command 1 and than 2' ]
            ]
        );
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!$this->isValid()) {
            return 1;
        }

        $this->exeCommand();

        return 0;        
    }


    public function command1Function()
    {
      $option = $this->argument('option');
      if ($option == null) {
           $option = 'NONE';
      }

      $this->info("Command 1 function with option {$option}");
    }

    public function command2Function()
    {
      $this->warn("Command 2 function");
    }

}
