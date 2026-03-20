<?php

declare(strict_types=1);

namespace Memory\Core\Command;

use Memory\Core\Storage\StorageInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand('memory:clear', 'Clear storage')]
class ClearCommand extends Command
{
    public function __construct(
        private readonly StorageInterface $storage
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if (!$this->storage->exists()) {
            $io->note('Storage is already empty.');
            return Command::SUCCESS;
        }

        $this->storage->clear();
        $io->success('Storage cleared.');

        return Command::SUCCESS;
    }
}
