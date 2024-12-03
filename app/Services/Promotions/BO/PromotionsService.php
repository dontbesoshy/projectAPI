<?php

namespace App\Services\Promotions\BO;

use App\Http\Dto\File\UploadedFileDto;
use App\Http\Dto\Promotion\UpdatePromotionStatusDto;
use App\Models\Promotion\Promotion;
use App\Resources\Promotion\BO\PromotionCollection;
use App\Services\BasicService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PromotionsService extends BasicService
{
    /**
     * Return promotions.
     *
     * @return PromotionCollection
     */
    public function index(): PromotionCollection
    {
        $promotions = Promotion::all()->sortByDesc('id');

        return new PromotionCollection($promotions);
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

            Storage::disk('public')->put($fileName, file_get_contents($dto->file));

            //$url = Storage::disk('public')->path($fileName);

            Promotion::query()->create([
                'url' => $fileName,
                'name' => $fileName,
            ]);

            DB::commit();
        } catch (\Throwable $e) {
            $this->rollBackThrow($e);
        }
    }

    /**
     * Delete promotion.
     *
     * @param Promotion $promotion
     *
     * @return void
     */
    public function delete(Promotion $promotion): void
    {
        $promotion->delete();
    }

    /**
     * Update status of promotion.
     *
     * @param UpdatePromotionStatusDto $dto
     *
     * @return void
     */
    public function updateStatus(UpdatePromotionStatusDto $dto): void
    {
        Promotion::query()
            ->whereIn('id', $dto->promotionIds)
            ->update(['active' => $dto->active]);
    }
}
