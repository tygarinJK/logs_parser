<?php

namespace App\Command;

use App\Services\LogsParser\FileGenerator\StreamedFileGenerator;
use App\Services\LogsParser\LogsParserServiceInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:import-logs',
    description: 'Import logs from a file to the database',
)]
class ImportLogsCommand extends Command
{
    public function __construct(
        private readonly LogsParserServiceInterface $logsParserService,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('file', InputArgument::REQUIRED, 'Path to log file')
            ->addArgument('num', InputArgument::OPTIONAL, 'Iteration size')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filepath = $input->getArgument('file');
        $num = $input->getArgument('num');

        if ($num && !is_numeric($num)) {
            $output->writeln('<error>Iteration size should be numeric.</error>');

            return Command::FAILURE;
        }

        try {
            $generator = new StreamedFileGenerator($filepath);

            $this->logsParserService->parseLogs($generator, (int) $num);

            $output->writeln('<info>Logs imported successfully.</info>');
        } catch (\Exception $e) {
            $output->writeln('<error>Error importing logs: ' . $e->getMessage() . '</error>');

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
