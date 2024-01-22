<?php

namespace App\Exceptions;

use App\Traits\JsonResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Monolog\Level;

abstract class BasicException extends \Exception
{
    use JsonResponseTrait;

    /* Should be overwritten in children exceptions */
    protected string $errorCode;

    protected int $errorHttpCode;

    protected $errorMsg;

    protected $errorData;

    protected array $loggerSettings = [
        'reportable' => true,
        'backtrace' => true,
    ];

    private string $errorUid;

    private array $moreData;

    protected bool $dataEqualToExceptionMoreData = false;

    protected string $translationKey;

    protected array $translationParams;

    protected Level $level = Level::Error;

    /**
     * BasicException constructor.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     * @param array $moreData
     */
    public function __construct($message = '', $code = 0, \Throwable $previous = null, $moreData = [])
    {
        parent::__construct($message, $code, $previous);

        $this->moreData = $moreData;
        $this->init();
        $this->errorUid = Str::uuid();

        $this->message = class_basename($this);
    }

    abstract protected function init();

    /**
     * Report function.
     */
    public function report(): bool
    {
        Log::log(
            $this->level->getName(),
            $this->getEnglishErrorMessage(),
            [
                'exception' => $this,
                'exceptionConfig' => $this->loggerSettings
            ],
        );

        return true;
    }

    /**
     * Render function.
     *
     * @param $request
     *
     * @return JsonResponse
     */
    public function render($request): JsonResponse
    {
        return $this->responseJson(
            $this->wrapError($this->errorMsg, $this->errorHttpCode, $this->errorCode, $this->errorUid, $this->errorData),
            $this->errorHttpCode
        );
    }

    /**
     * Get exception context.
     *
     * @return array
     */
    private function getExceptionContext(): array
    {
        if (method_exists($this, 'context')) {
            return $this->context();
        }

        return [];
    }

    /**
     * Get error code.
     *
     * @return string
     */
    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    /**
     * Get error msg.
     *
     * @return string
     */
    public function getErrorMsg(): string
    {
        return $this->errorMsg;
    }

    /**
     * Get error http code.
     *
     * @return int
     */
    public function getErrorHttpCode(): int
    {
        return $this->errorHttpCode;
    }

    /**
     * Get more data field.
     *
     * @return array
     */
    public function getMoreData(): array
    {
        return $this->moreData;
    }

    /**
     * Get error data field.
     *
     * @return mixed
     */
    public function getErrorData(): mixed
    {
        return $this->errorData;
    }

    /**
     * Return if is reportable.
     *
     * @return bool
     */
    public function isReportable(): bool
    {
        return $this->loggerSettings['reportable'];
    }

    /**
     * Get uuid of an error.
     *
     * @return string
     */
    public function getUuid(): string
    {
        return $this->errorUid;
    }

    /**
     * Get error message.
     *
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMsg;
    }

    /**
     * Get english error message.
     *
     * @return string
     */
    public function getEnglishErrorMessage(): string
    {
        return __($this->translationKey, $this->translationParams, 'en');
    }

    /**
     * Get translation data.
     *
     * @return array
     */
    public function getTranslationData(): array
    {
        return [
            'key' => $this->translationKey,
            'params' => $this->translationParams,
        ];
    }

    /**
     * Translate error msg and save translation params.
     *
     * @param string $key
     * @param array $params
     *
     * @return string
     */
    protected function __(string $key, array $params = []): string
    {
        $this->translationKey = $key;
        $this->translationParams = $params;

        return __($key, $params);
    }
}
