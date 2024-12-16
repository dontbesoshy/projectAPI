<?php

namespace App\Services\User\AD;

use App\Http\Dto\User\AD\FavoritePartsDto;
use App\Http\Dto\User\AD\NewPasswordDto;
use App\Models\User\User;
use App\Resources\User\AD\PartCollection;
use App\Services\BasicService;
use Illuminate\Support\Facades\DB;

class UserService extends BasicService
{
    /**
     * Set a new password for the user.
     *
     * @param User $user
     * @param NewPasswordDto $dto
     *
     * @return void
     */
    public function setNewPassword(User $user, NewPasswordDto $dto): void
    {
        $user->update([
            'password' => bcrypt($dto->newPassword),
        ]);
    }

    /**
     * Sync favorite parts.
     *
     * @param User $user
     * @param FavoritePartsDto $dto
     *
     * @return void
     */
    public function syncFavoriteParts(User $user, FavoritePartsDto $dto): void
    {
        DB::beginTransaction();
        try {
            $user->favoriteParts()->delete();

            foreach ($dto->partEans as $partEan) {
                $user->favoriteParts()->create([
                    'ean' => $partEan,
                ]);
            }

            DB::commit();
        } catch (\Throwable $th) {
            $this->rollBackThrow($th);
        }
    }

    /**
     * Get favorite parts.
     *
     * @param User $user
     *
     * @return PartCollection
     */
    public function getFavoriteParts(User $user): PartCollection
    {
        $parts = $user
            ->priceLists
            ->first()
            ->partsWithTrashed
            ->filter(fn($part) => in_array($part->ean, $user->favoriteParts->pluck('ean')->toArray()))
            ->groupBy('ean')
            ->map(function ($groupedParts) {
                return $groupedParts->first(fn($part) => !$part->trashed()) ?? $groupedParts->first();
            })
            ->values();

        return new PartCollection($parts);
    }
}
