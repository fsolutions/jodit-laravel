<?php

namespace Do6po\LaravelJodit\Dto;

use Illuminate\Support\Collection;

class FolderDto
{
    protected string $name;
    protected string $url;
    protected array $folders;
    protected Collection $files;
    protected string $path;

    public function __construct(string $name, string $url, array $folders, array $files, string $path)
    {
        $this->name = $name;
        $this->url = $url;
        $this->folders = $folders;
        $this->files = collect($files);
        $this->path = $path;
    }

    public static function byParams(string $name, string $url, array $folders, array $files, string $path): self
    {
        return new self($name, $url, $folders, $files, $path);
    }

    public function toArray(): array
    {
        return [
            'name'    => $this->name,
            'url'     => $this->url,
            'baseurl' => $this->url,
            'folders' => $this->folders,
            'files'   => $this->files->map->toArray()->all(),
            'path'    => $this->path
        ];
    }
}
