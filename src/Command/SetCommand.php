<?php

declare(strict_types=1);

namespace Memory\Core\Command;

use Memory\Core\Storage\StorageInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('memory:set', 'Set value in storage')]
class SetCommand extends Command
{
    public function __construct(
        private readonly StorageInterface $storage
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('key', InputArgument::REQUIRED, 'Key to set')
            ->addArgument('value', InputArgument::REQUIRED, 'Value to set');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $key = $input->getArgument('key');
        $value = $input->getArgument('value');

        $parsedValue = $this->parseValue($value);
        $this->storage->set($key, $parsedValue);

        $displayValue = is_array($parsedValue)
            ? json_encode($parsedValue, JSON_UNESCAPED_UNICODE)
            : $value;
        $output->writeln("<info>Set: $key = $displayValue</info>");

        return Command::SUCCESS;
    }

    private function parseValue(string $value): mixed
    {
        if (in_array($value, ['true', 'false'], true)) {
            return $value === 'true';
        }

        if (is_numeric($value)) {
            return str_contains($value, '.') ? (float) $value : (int) $value;
        }

        if (str_starts_with($value, '[') || str_starts_with($value, '{')) {
            $decoded = json_decode($value, true);
            if ($decoded !== null) {
                return $decoded;
            }
        }

        return $value;
    }
}
