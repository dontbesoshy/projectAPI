<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait CanThrowTrait
{
    /**
     * Function to throw exceptions.
     *
     * @param $exception
     */
    protected static function throwStatic($exception): void
    {
        throw $exception;
    }

    /**
     * Throw if condition.
     *
     * @param $condition
     * @param $exceptionClass
     * @param mixed ...$args
     */
    protected static function throwStaticIf($condition, $exceptionClass, ...$args): void
    {
        if ($condition) {
            self::throwStatic(new $exceptionClass(...$args));
        }
    }

    /**
     * Function to throw exceptions.
     *
     * @param $exception
     */
    protected function throw($exception): void
    {
        throw $exception;
    }

    /**
     * Throw if condition.
     *
     * @param $condition
     * @param $exceptionClass
     * @param mixed ...$args
     */
    protected function throwIf($condition, $exceptionClass, ...$args): void
    {
        if ($condition) {
            $this->throw(new $exceptionClass(...$args));
        }
    }

    /**
     * Throw if not.
     *
     * @param $condition
     * @param $exceptionClass
     * @param mixed ...$args
     */
    protected function throwIfNot($condition, $exceptionClass, ...$args): void
    {
        $this->throwIf(!$condition, $exceptionClass, ...$args);
    }

    /**
     * Roll back and throw exception.
     *
     * @param \Throwable $exception
     */
    protected function rollBackThrow(\Throwable $exception): void
    {
        DB::rollBack();
        $this->throw($exception);
    }
}
