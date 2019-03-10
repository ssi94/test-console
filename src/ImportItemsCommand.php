<?php

namespace App;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class ImportItemsCommand extends Command
{

    public function configure() {
        $this->setName('import')
            ->setDescription('Import csv file to mysql database')
            ->addArgument('fileName', InputArgument::REQUIRED, 'Csv file name.');
    }

    public function execute(InputInterface $input, OutputInterface $output) {
        $message = "Reading file: " . $input->getArgument('fileName');
        $output->writeln("<info>{$message}</info>");
    }

}