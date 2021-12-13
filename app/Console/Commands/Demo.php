<?php
namespace App\Console\Commands;

use sachkov\lenvendoconsolelib\Commands as libCommands;
use sachkov\lenvendoconsolelib as clib;

class Demo extends libCommands\AbstractCommand
{
    public $name = "демо-команда";
    public $description = "вывод на экран параметров и аргументов команды";
    private $console;

    function __construct(clib\Console $com)
    {
        parent::__construct($com);

        $this->console = $com;
    }

    public function execute()
    {
        echo "Colled command: ".$this->console->getCommandName().PHP_EOL;
        echo PHP_EOL;
        
        if(count($this->args)){
            echo "Arguments:".PHP_EOL;
            foreach($this->args as $arg){
                echo "  - ".$arg.PHP_EOL;
            }
        }else{
            echo "Arguments empty.".PHP_EOL;
        }
        echo PHP_EOL;

        if(count($this->params)){
            echo "Options:".PHP_EOL;
            foreach($this->params as $name=>$values){
                echo "  - ".$name.PHP_EOL;
                foreach($values as $val){
                    echo "      - ".$val.PHP_EOL;
                }
            }
        }else{
            echo "Options empty.".PHP_EOL;
        }
    }
}
        