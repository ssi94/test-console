<?php

namespace App;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

class ImportItemsCommand extends Command
{

    public function configure() {
        $this->setName('import')
            ->setDescription('Import csv file to mysql database')
            ->addArgument('fileName', InputArgument::REQUIRED, 'Csv file name.');
    }

    public function execute(InputInterface $input, OutputInterface $output) {
        $fileName = $input->getArgument('fileName');
        $message = "Reading file: " . $fileName. "\n";
        $output->writeln("<info>{$message}</info>");
        if (($handle = @fopen($fileName, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
                $importData[] = $data;
            }
            [$header, ] = $importData;
            $importData = array_slice($importData, 1);
            $this->showImportData($header, $importData, $output);
        } else {
            $output->writeln("<error>File \"{$fileName}\" cannot be read or doesn't exist.</error>");
        }
    }

    private function showImportData($header, $importData, $output) {
        $table = new Table($output);
        $table->setHeaders($header)->setRows($importData);
        $table->render();
    }

}