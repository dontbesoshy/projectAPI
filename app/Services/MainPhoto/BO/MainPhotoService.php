<?php

namespace App\Services\MainPhoto\BO;

use App\Exceptions\MainPhoto\MainPhotoNotFoundException;
use App\Http\Dto\File\UploadedFileDto;
use App\Models\MainPhoto;
use App\Resources\MainPhoto\BO\MainPhotoResource;
use App\Services\BasicService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MainPhotoService extends BasicService
{
    /**
     * Return main photo.
     *
     * @return MainPhotoResource
     */
    public function index(): MainPhotoResource
    {
        $mainPhoto = MainPhoto::all()->last();

        $this->throwIf(
            !$mainPhoto,
            MainPhotoNotFoundException::class
        );

        return new MainPhotoResource($mainPhoto);
    }

    /**
     * Store a new main photo.
     *
     * @param UploadedFileDto $dto
     *
     * @return void
     */
    public function create(UploadedFileDto $dto): void
    {
        DB::beginTransaction();

        try {
            $fileName = $dto->file->getClientOriginalName();

            Storage::disk('local')->put('public/'.$fileName, file_get_contents($dto->file));

            $url = Storage::disk('local')->path('public/'.$fileName);

            MainPhoto::query()->create([
                'url' => 'storage/'.$fileName,
                'name' => $fileName,
            ]);

            Storage::setVisibility($url, 'public');

            DB::commit();
        } catch (\Throwable $e) {
            $this->rollBackThrow($e);
        }
    }
}
