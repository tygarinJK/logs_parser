<?php

namespace App\Command;

use App\Message\LogBatchMessage;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(name: 'app:import-logs', description: 'Parses a log file and imports records into DB')]
class ImportLogsCommand extends Command
{
    public const BATCH_SIZE = 10;

    public function __construct(
        private readonly MessageBusInterface $bus
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('file', InputArgument::REQUIRED, 'Path to log file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filePath = $input->getArgument('file');

        if (!file_exists($filePath)) {
            $output->writeln("<error>File not found</error>");

            return Command::FAILURE;
        }

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            $output->writeln("<error>Unable to read file</error>");

            return Command::FAILURE;
        }

        $batch = [];
        $i = 0;

        while (($line = fgets($handle)) !== false) {
            $line = trim($line);

            if ($line === '') {
                continue;
            }

            $batch[] = $line;

            if (count($batch) === self::BATCH_SIZE) {
                try {
                    $this->bus->dispatch(new LogBatchMessage($batch));

                    $i++;
                    $output->writeln("Dispatched $i records...");
                } catch (ExceptionInterface $e) {
                    $output->writeln("<error>Failed to dispatch batch: {$e->getMessage()}</error>");
                }

                $batch = [];
            } else {
                $i++;
            }
        }

        if (!empty($batch)) {
            // Send remaining lines
            $this->bus->dispatch(new LogBatchMessage($batch));
        }

        fclose($handle);
        $output->writeln("<info>Done. Processed $i lines.</info>");

        return Command::SUCCESS;
    }
}