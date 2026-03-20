<?php

declare(strict_types=1);

namespace Memory\Core\Command;

use Memory\Core\Storage\StorageInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('memory:get', 'Get value from storage')]
class GetCommand extends Command
{
    public function __construct(
        private readonly StorageInterface $storage
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('key', InputArgument::REQUIRED, 'Key to retrieve');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $key = $input->getArgument('key');

        if (!$this->storage->has($key)) {
            $output->writeln("<error>Key not found: $key</error>");
            return Command::FAILURE;
        }

        $value = $this->storage->get($key);

        if (is_array($value)) {
            $output->writeln(json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        } else {
            $output->writeln((string) $value);
        }

        return Command::SUCCESS;
    }
}
