<?php

namespace Do6po\LaravelJodit\Actions;

use Carbon\Carbon;
use Do6po\LaravelJodit\Dto\FileDto;
use Do6po\LaravelJodit\Dto\FolderDto;
use Illuminate\Support\Facades\Storage;
use Do6po\LaravelJodit\Http\Resources\DirectoryResource;

class Files extends AbstractFileBrowserAction
{
    protected FolderDto $folder;

    public static function getActionName(): string
    {
        return 'files';
    }

    public function handle(): FileBrowserAction
    {
        $path = $this->getPath();
        $this->mapFiles($path);
        return $this;
    }

    protected function mapFiles(string $path)
    {
        $rootPath = config('jodit.root_public') . '/'. $path;
        $files = [];

        foreach (Storage::disk('public')->files($rootPath) as $filePath) {
            $attributes = $this->getAttributesByPath($filePath);
            $files[] = FileDto::byAttributes($attributes);
        }

        $this->folder = FolderDto::byParams(
            basename($path),
            Storage::url($path),
            [],
            $files,
            $rootPath
        );
    }

    protected function getAttributesByPath(string $filePath): array
    {
        return [
            'name' => basename($filePath),
            'thumb'    => $this->isImage($filePath) ? Storage::url($filePath) : '',
            'changed'  => $this->getChangedTimeByFilePath($filePath),
            'size'     => Storage::disk('public')->size($filePath),
            'type'     => $this->getType($filePath),
        ];
    }

    protected function getType(string $filePath): string
    {
        return $this->isImage($filePath) ? 'image' : 'file';
    }

    private function isImage($filePath): bool
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg']);
    }

    protected function getChangedTimeByFilePath(string $filePath): Carbon
    {
        return Carbon::createFromTimestamp(Storage::disk('public')->lastModified($filePath));
    }

    public function response(): DirectoryResource
    {
        return DirectoryResource::make($this->folder);
    }
}
