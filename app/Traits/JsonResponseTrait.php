<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait JsonResponseTrait
{
    /**
     * Return response in json.
     *
     * @param $data
     * @param $code
     *
     * @return JsonResponse
     */
    public function responseJson($data, $code): JsonResponse
    {
        return response()->json($data, $code);
    }

    /**
     * Function that adds extra fields to result response.
     *
     * @param array $arr
     *
     * @return array
     */
    private function sanitizeResult(array $arr): array
    {
        return $arr;
    }

    /**
     * @param $data
     *
     * @return array
     */
    private function wrapSuccess($data): array
    {
        return $this->sanitizeResult([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Wrap error to specified structure.
     *
     * @param string $errorMsg
     * @param int $httpCode
     * @param string $errorCode
     * @param string $errorUid
     * @param $errorData
     *
     * @return array
     */
    private function wrapError(string $errorMsg, int $httpCode, string $errorCode, string $errorUid = '', $errorData = ''): array
    {
        $array = [
            'success' => false,
            'httpCode' => $httpCode,
            'error' => [
                'code' => $errorCode,
                'message' => $errorMsg,
            ],
        ];

        if ($errorUid != '') {
            $array['error']['uid'] = $errorUid;
        }

        if ($errorData != '') {
            $array['error']['data'] = $errorData;
        }

        return $this->sanitizeResult($array);
    }

    /**
     * 200 - ok.
     *
     * @param array $data
     *
     * @return JsonResponse
     */
    protected function OK($data = []): JsonResponse
    {
        return $this->responseJson($this->wrapSuccess($data), Response::HTTP_OK);
    }

    /**
     * 201 - created.
     *
     * @param array $data
     *
     * @return JsonResponse
     */
    protected function CREATED($data = []): JsonResponse
    {
        return $this->responseJson($this->wrapSuccess($data), Response::HTTP_CREATED);
    }

    /**
     * 202 - accepted.
     *
     * @param array $data
     *
     * @return JsonResponse
     */
    protected function ACCEPTED($data = []): JsonResponse
    {
        return $this->responseJson($this->wrapSuccess($data), Response::HTTP_ACCEPTED);
    }

    /**
     * 204 - no content.
     *
     * @param array $data
     *
     * @return JsonResponse
     */
    protected function NO_CONTENT($data = []): JsonResponse
    {
        return $this->responseJson($this->wrapSuccess($data), Response::HTTP_NO_CONTENT);
    }

    /**
     * 401 - unauthorized.
     *
     * @param string $error
     * @param string $errorCode
     *
     * @return JsonResponse
     */
    protected function UNAUTHORIZED(string $error = '', string $errorCode = ''): JsonResponse
    {
        if ($error == '') {
            $error = __('httpRequests.401');
        }

        return $this->responseJson(
            $this->wrapError($error, Response::HTTP_UNAUTHORIZED, $errorCode),
            Response::HTTP_UNAUTHORIZED
        );
    }

    /**
     * 403 - forbidden.
     *
     * @param string $error
     * @param string $errorCode
     *
     * @return JsonResponse
     */
    protected function FORBIDDEN(string $error = '', string $errorCode = ''): JsonResponse
    {
        if ($error == '') {
            $error = __('httpRequests.403');
        }

        return $this->responseJson(
            $this->wrapError($error, Response::HTTP_FORBIDDEN, $errorCode),
            Response::HTTP_FORBIDDEN
        );
    }

    /**
     * 404 - not found.
     *
     * @param string $error
     * @param string $errorCode
     *
     * @return JsonResponse
     */
    protected function NOT_FOUND(string $error = '', string $errorCode = ''): JsonResponse
    {
        if ($error == '') {
            $error = __('httpRequests.404');
        }

        return $this->responseJson(
            $this->wrapError($error, Response::HTTP_NOT_FOUND, $errorCode),
            Response::HTTP_NOT_FOUND
        );
    }

    /**
     * 409 - conflict.
     *
     * @param string $error
     * @param string $errorCode
     *
     * @return JsonResponse
     */
    protected function CONFLICT(string $error = '', string $errorCode = ''): JsonResponse
    {
        if ($error == '') {
            $error = __('httpRequests.409');
        }

        return $this->responseJson(
            $this->wrapError($error, Response::HTTP_CONFLICT, $errorCode),
            Response::HTTP_CONFLICT
        );
    }

    /**
     * 422 - unprocessable.
     *
     * @param string $error
     * @param string $errorCode
     *
     * @return JsonResponse
     */
    protected function UNPROCESSABLE(string $error = '', string $errorCode = ''): JsonResponse
    {
        if ($error == '') {
            $error = __('httpRequests.422');
        }

        return $this->responseJson(
            $this->wrapError($error, Response::HTTP_UNPROCESSABLE_ENTITY, $errorCode),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /**
     * 500 - internal server error.
     *
     * @param string $error
     * @param string $errorCode
     *
     * @return JsonResponse
     */
    protected function INTERNAL_SERVER_ERROR(string $error = '', string $errorCode = ''): JsonResponse
    {
        if ($error == '') {
            $error = __('httpRequests.500');
        }

        return $this->responseJson(
            $this->wrapError($error, Response::HTTP_INTERNAL_SERVER_ERROR, $errorCode),
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
