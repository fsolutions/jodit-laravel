<?php

namespace Do6po\LaravelJodit\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Resources\Json\JsonResource;
use Do6po\LaravelJodit\Factories\FileUploadFactory;
use Do6po\LaravelJodit\Http\Requests\FileUploadRequest;
use Do6po\LaravelJodit\Http\Requests\FileBrowserRequest;
use Do6po\LaravelJodit\Factories\FileManipulationFactory;
use Do6po\LaravelJodit\Factories\NotFoundActionException;

class JoditController extends Controller
{
    /**
     * @throws NotFoundActionException
     */
    public function upload(FileUploadRequest $request, FileUploadFactory $factory): JsonResource
    {
        return $factory
            ->create($request->getDto())
            ->handle()
            ->response();
    }

    /**
     * @throws NotFoundActionException
     */
    public function browse(FileBrowserRequest $request, FileManipulationFactory $factory): JsonResource
    {
        return $factory
            ->create($request->getDto())
            ->handle()
            ->response();
    }
}
