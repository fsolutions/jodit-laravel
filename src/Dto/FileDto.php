<?php

namespace Do6po\LaravelJodit\Dto;

class FileDto
{
    protected string $name;
    protected ?string $thumb;
    protected string $changed;
    protected int $size;
    protected string $type;

    public function __construct(string $name, ?string $thumb, string $changed, int $size, string $type)
    {
        $this->name = $name;
        $this->thumb = $thumb;
        $this->changed = $changed;
        $this->size = $size;
        $this->type = $type;
    }

    public static function byAttributes(array $attributes): self
    {
        return new self(
            $attributes['name'],
            $attributes['thumb'],
            $attributes['changed'],
            $attributes['size'],
            $attributes['type']
        );
    }

    public function toArray(): array
    {
        return [
            'fileName' => $this->name,
            'name' => $this->name,
            'type' => $this->type,
            'url' => $this->thumb,
            'thumb' => basename($this->thumb),
            'changed' => $this->changed,
            'size' => $this->size,
        ];
    }
}
