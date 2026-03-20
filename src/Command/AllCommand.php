<?php

declare(strict_types=1);

namespace Memory\Core\Command;

use Memory\Core\Storage\StorageInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('memory:all', 'Show all stored values')]
class AllCommand extends Command
{
    public function __construct(
        private readonly StorageInterface $storage
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $data = $this->storage->all();

        if (empty($data)) {
            $output->writeln('<comment>Storage is empty</comment>');
            return Command::SUCCESS;
        }

        $output->writeln(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return Command::SUCCESS;
    }
}
