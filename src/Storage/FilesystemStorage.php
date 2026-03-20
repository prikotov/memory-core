<?php

declare(strict_types=1);

namespace Memory\Core\Storage;

final class FilesystemStorage implements StorageInterface
{
    private string $dataFile;

    public function __construct(string $dataDir, string $filename = 'data.json')
    {
        if (!is_dir($dataDir)) {
            mkdir($dataDir, 0755, true);
        }
        $this->dataFile = rtrim($dataDir, '/') . '/' . $filename;
    }

    public function get(string $key): mixed
    {
        $data = $this->load();
        return $data[$key] ?? null;
    }

    public function set(string $key, mixed $value): void
    {
        $data = $this->load();
        $data[$key] = $value;
        $this->save($data);
    }

    public function has(string $key): bool
    {
        $data = $this->load();
        return array_key_exists($key, $data);
    }

    public function delete(string $key): void
    {
        $data = $this->load();
        if (array_key_exists($key, $data)) {
            unset($data[$key]);
            $this->save($data);
        }
    }

    public function all(): array
    {
        return $this->load();
    }

    public function clear(): void
    {
        if (file_exists($this->dataFile)) {
            unlink($this->dataFile);
        }
    }

    public function exists(): bool
    {
        return file_exists($this->dataFile);
    }

    public function getDataFile(): string
    {
        return $this->dataFile;
    }

    private function load(): array
    {
        if (!file_exists($this->dataFile)) {
            return [];
        }

        $content = file_get_contents($this->dataFile);
        $data = json_decode($content, true);

        return is_array($data) ? $data : [];
    }

    private function save(array $data): void
    {
        file_put_contents(
            $this->dataFile,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }
}
