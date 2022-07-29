<?php
namespace App\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Migrations\Common as MigrationRunner;

class Migrations extends Command
{
    protected static $defaultName = 'make:migrations';
    protected static $defaultDescription = 'Start migrations';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Command starts');

        try{
            (new MigrationRunner())->execute();
        }catch (\Exception $e){
            $output->writeln('migration error: '.$e->getMessage());
            return Command::FAILURE;
        }

        $output->writeln('All migrations done.');

        return Command::SUCCESS;
    }
}