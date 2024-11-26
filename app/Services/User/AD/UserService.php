<?php

namespace App\Services\User\AD;

use App\Http\Dto\User\AD\FavoritePartsDto;
use App\Http\Dto\User\AD\NewPasswordDto;
use App\Models\User\User;
use App\Resources\User\AD\PartCollection;
use App\Services\BasicService;

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
        $user->favoriteParts()->delete();

        foreach ($dto->partIds as $favoritePart) {
            $user->favoriteParts()->create([
                'part_id' => $favoritePart,
            ]);
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
        $parts = $user->favoriteParts()->get()->map(function ($part) {
            return $part->part;
        });

        return new PartCollection($parts);
    }
}
