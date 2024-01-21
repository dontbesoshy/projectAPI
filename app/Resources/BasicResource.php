<?php

namespace App\Resources;

use App\Traits\CanThrowTrait;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

abstract class BasicResource extends JsonResource
{
    use CanThrowTrait;

    public static array $additionalData = [];

    /**
     * Check if flag exists in additional data.
     *
     * @param string $name
     *
     * @return bool
     */
    protected static function additionalFlagExists(string $name): bool
    {
        return isset(self::$additionalData[$name]) && self::$additionalData[$name];
    }

    /**
     * Get additional data.
     *
     * @param string $name
     *
     * @return mixed
     */
    protected static function getAdditionalData(string $name): mixed
    {
        return self::$additionalData[$name] ?? null;
    }

    /**
     * When relation loaded method.
     *
     * @param mixed $relationship
     * @param string $resourceClassName
     * @param string|null $relationshipForResource
     *
     * @return mixed
     */
    protected function whenRelationLoaded(
        mixed $relationship,
        string|\Closure $resourceClassName,
        string $relationshipForResource = null
    ): mixed {
        $object = $this;

        if (is_array($relationship)) {
            $object = $relationship[0];
            $relationship = $relationship[1];
        }

        if (str_contains($relationship, '.')) {
            $relationshipParts = explode('.', $relationship);
            $relationshipPartObject = $object;
            $shouldBeShown = true;

            foreach ($relationshipParts as $relationshipPart) {
                if (!$relationshipPartObject->relationLoaded($relationshipPart)) {
                    $shouldBeShown = false;
                    break;
                }

                $relationshipPartObject = $relationshipPartObject->{$relationshipPart};
                $this->throwIf(
                    $relationshipPartObject instanceof Collection,
                    \InvalidArgumentException::class,
                    'Cannot use nested relationship with hasMany'
                );
            }

            $resourceCallback = $resourceClassName instanceof \Closure
                ? fn () => $resourceClassName($relationshipPartObject)
                : fn () => new $resourceClassName($relationshipPartObject);
        } else {
            $shouldBeShown = $object->relationLoaded($relationship);
            $resourceCallback = $resourceClassName instanceof \Closure
                ? fn () => $resourceClassName($object->{$relationshipForResource ?? $relationship})
                : fn () => new $resourceClassName($object->{$relationshipForResource ?? $relationship});
        }

        return $this->when(
            $shouldBeShown,
            $resourceCallback
        );
    }
}
