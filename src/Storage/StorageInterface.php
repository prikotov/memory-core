<?php

declare(strict_types=1);

namespace Memory\Core\Storage;

interface StorageInterface
{
    public function get(string $key): mixed;

    public function set(string $key, mixed $value): void;

    public function has(string $key): bool;

    public function delete(string $key): void;

    public function all(): array;

    public function clear(): void;

    public function exists(): bool;
}
