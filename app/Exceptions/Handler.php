<?php

namespace App\Exceptions;

use App\Exceptions\General\AuthenticationException as MyAuthenticationException;
use App\Exceptions\General\ModelNotFoundException as MyModelNotFoundException;
use App\Exceptions\General\NotFoundHttpException as MyNotFoundHttpException;
use App\Traits\CanThrowTrait;
use App\Traits\JsonResponseTrait;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use JsonResponseTrait;

    use CanThrowTrait;

    /**
     * Map exceptions to out custom ones.
     *
     * @var array|string[]
     */
    private array $myExceptionsMap = [
        AuthenticationException::class => MyAuthenticationException::class,
        NotFoundHttpException::class => MyNotFoundHttpException::class,
        ModelNotFoundException::class => MyModelNotFoundException::class,
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthenticationException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->myExceptionsMap as $from => $to) {
            $this->map($from, $to);
        }
    }

    /**
     * Report exception.
     *
     * @param Throwable $e
     *
     * @return void
     *
     * @throws Throwable
     */
    public function report(Throwable $e): void
    {
        $e = $this->mapException($e);

        // If it is our exception and is not reportable -> return
        if ($e instanceof BasicException && !$e->isReportable()) {
            return;
        }

        parent::report($e);
    }

    /**
     * Override render method.
     *
     * @param $request
     * @param Throwable $e
     *
     * @return mixed
     *
     * @throws Throwable
     */
    public function render($request, Throwable $e): mixed
    {
        $e = $this->mapException($e);

        return parent::render($request, $e);
    }
}
