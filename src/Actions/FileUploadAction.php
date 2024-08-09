<?php

namespace Do6po\LaravelJodit\Actions;

use Do6po\LaravelJodit\Dto\UploadedFileDto;
use Do6po\LaravelJodit\Dto\UploadedFilesInfoDto;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Do6po\LaravelJodit\Http\Resources\FileUploadResource;

class FileUploadAction extends AbstractFileUploadAction
{
    private UploadedFilesInfoDto $uploadFilesInfo;

    public static function getActionName(): string
    {
        return 'fileUpload';
    }

    public function handle(): FileBrowserAction
    {
        $this->checkFilesExists();
        if ($this->hasErrors()) {
            return $this;
        }

        $this->storeFiles();
        return $this;
    }

    private function checkFilesExists(): void
    {
        $path = $this->getPath();
        foreach ($this->getFiles() as $file) {
            $newFilePath = $this->generateNewFilePath($path, $file->getClientOriginalName());
            $this->checkPathExists($newFilePath);
        }
    }

    private function getFiles(): array
    {
        return $this->dto->getFiles();
    }

    protected function generateNewFilePath(string $path, string $fileName): string
    {
        return $path . DIRECTORY_SEPARATOR . $this->replaceSpecialCharacters($fileName);
    }

    public function storeFiles(): void
    {
        $path = $this->getPath();
        $uploadedFiles = [];

        foreach ($this->getFiles() as $file) {
            $newFilePath = $this->generateNewFilePath($path, $file->getClientOriginalName());
            $this->fileBrowser->put($newFilePath, file_get_contents($file->getRealPath()));

            $uploadedFile = UploadedFileDto::byFilePath($newFilePath);
            $uploadedFiles[$uploadedFile->getName()] = $uploadedFile;
        }

        $this->uploadFilesInfo = UploadedFilesInfoDto::byDirUrlAndFiles(
            $this->fileBrowser->getUrl($path),
            $uploadedFiles
        );
    }

    public function response()
    {
        if ($this->hasErrors()) {
            return $this->getErrorResponse();
        }

        return FileUploadResource::make($this->uploadFilesInfo);
    }
}
