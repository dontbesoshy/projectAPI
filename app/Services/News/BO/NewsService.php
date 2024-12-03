<?php

namespace App\Services\News\BO;

use App\Http\Dto\File\UploadedFileDto;
use App\Http\Dto\News\UpdateNewsStatusDto;
use App\Models\News\News;
use App\Resources\News\BO\NewsCollection;
use App\Services\BasicService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class NewsService extends BasicService
{
    /**
     * Return news.
     *
     * @return NewsCollection
     */
    public function index(): NewsCollection
    {
        $promotions = News::all()->sortByDesc('id');

        return new NewsCollection($promotions);
    }

    /**
     * Store a new news.
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

            Storage::disk('public')->put($fileName, file_get_contents($dto->file));

            //$url = Storage::disk('public')->path($fileName);

            News::query()->create([
                'url' => $fileName,
                'name' => $fileName,
            ]);

            DB::commit();
        } catch (\Throwable $e) {
            $this->rollBackThrow($e);
        }
    }

    /**
     * Delete news.
     *
     * @param News $promotion
     *
     * @return void
     */
    public function delete(News $promotion): void
    {
        $promotion->delete();
    }

    /**
     * Update status of news.
     *
     * @param UpdateNewsStatusDto $dto
     *
     * @return void
     */
    public function updateStatus(UpdateNewsStatusDto $dto): void
    {
        News::query()
            ->whereIn('id', $dto->newsIds)
            ->update(['active' => $dto->active]);
    }
}
