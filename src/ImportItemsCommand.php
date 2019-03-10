<?php

namespace App;

use App\Command;
use App\DatabaseAdapter;
use App\ItemValidator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Helper\Table;

class ImportItemsCommand extends Command
{
    public function __construct(DatabaseAdapter $db) {
        parent::__construct($db);
    }

    public function configure() {
        $this->setName('import')
            ->setDescription('Import csv file to mysql database')
            ->addArgument('fileName', InputArgument::REQUIRED, 'Csv file name.')
            ->addOption('test', null, InputOption::VALUE_NONE,
                'Import in "test mode" (only validate and show stats)');
    }

    public function execute(InputInterface $input, OutputInterface $output) {
        $fileName = $input->getArgument('fileName');
        $testMode = $input->getOption('test') ?: false;
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
            exit(1);
        }
        $date = date("Y-m-d H:i:s",time());
        $inserted = [];
        $invalid = [];
        if($testMode) {
            $this->db->beginTransaction();
        }
        foreach($importData as $item) {
            @[$code, $name, $description, $stock, $price, $discontinued_at] = $item;
            $discontinued_at = $discontinued_at === "yes" ? $date : null;
            $parsedItem = compact('code', 'name', 'description', 'stock', 'price', 'discontinued_at');
            $hasErrors = ItemValidator::validate($parsedItem);
            if($hasErrors === false) {
                $successfuly = $this->db->insertItem($parsedItem);
                if($successfuly) {
                    $inserted[] = array_values($item);
                } else {
                    $invalid[] = [$code, "A product with this code already exists"];
                }
            } else {
                $invalid[] = [$code, $hasErrors];
            }
        }
        $this->showImportReport($inserted, $header, $invalid, $output);
        if($testMode) {
            $this->db->rollBack();
            $output->writeln("<error>Import was made in \"test mode\", no actual changes to the database were made!</error>");
        }
    }

    private function showImportData($header, $importData, $output) {
        $output->writeln("<info>Data from CSV to be imported</info>");
        $table = new Table($output);
        $table->setHeaders($header)->setRows($importData);
        $table->render();
        $output->writeln("\n\n");
    }

    private function showImportReport($inserted, $header, $invalid, $output) {
        if(!empty($invalid)) {
            $output->writeln("<info>Data not inserted</info>");
            $table = new Table($output);
            $table->setheaders(['Product code', 'Errors'])->setRows($invalid);
            $table->render();
        } else {
            $output->writeln("<info>No errors occured</info>");
        }
        $output->writeln("\n\n");
        if(!empty($inserted)) {
            $output->writeln("<info>Data succesfuly inserted</info>");
            $table = new Table($output);
            $table->setHeaders($header)->setRows($inserted);
            $table->render();
        } else {
            $output->writeln("<info>No items were imported</info>");
        }

        $output->writeln("\n\n");
    
    }
}