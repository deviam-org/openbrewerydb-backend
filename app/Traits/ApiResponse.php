<?php

declare(strict_types=1);

namespace App\Traits;

use Exception;
use JsonSerializable;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

trait ApiResponse
{
    /**
     * @param  string|null  $message
     * @return JsonResponse
     */
    public function respondNotFound(
        string $message,
    ): JsonResponse {
        return $this->apiResponse(
            [
                'success' => false,
                'message' => $message ?? 'Data not found',
                'error' => $message ?? 'Data not found',
            ],
            Response::HTTP_NOT_FOUND
        );
    }

    /**
     * @param  array|Arrayable|JsonSerializable  $data
     * @param  array|Collection|LengthAwarePaginator  $metaData
     * @param  string  $message
     * @return JsonResponse
     */
    public function respondSuccess(
        array|Arrayable|JsonSerializable $data,
        array|Collection|LengthAwarePaginator $metaData,
        string $message
    ): JsonResponse {
        return $this->apiResponse(
            [
                'success' => true,
                'message' => $message,
                'meta' => $metaData,
                'data' => $this->morphToArray($data) ?? []
            ],
            Response::HTTP_OK
        );
    }

    /**
     * @param  string|null  $message
     * @return JsonResponse
     */
    public function respondError(
        string $message
    ): JsonResponse {
        return $this->apiResponse(
            [
                'success' => false,
                'message' => $message ?? 'Server Error',
                'error' => $message ?? 'Server Error',
                'errors' => []
            ],
            Response::HTTP_BAD_REQUEST
        );
    }

    /**
     * @param  string|null  $message
     * @return JsonResponse
     */
    public function respondUnAuthenticated(
        string $message
    ): JsonResponse {
        return $this->apiResponse(
            [
                'success' => false,
                'message' => $message ?? 'Unauthenticated',
                'error' => $message ?? 'Unauthenticated'
            ],
            Response::HTTP_UNAUTHORIZED
        );
    }

    /**
     * @param  string $message
     * @return JsonResponse
     */
    public function respondForbidden(
        string $message
    ): JsonResponse {
        return $this->apiResponse(
            [
                'success' => false,
                'message' => $message ?? 'Forbidden',
                'error' => $message ?? 'Forbidden'
            ],
            Response::HTTP_FORBIDDEN
        );
    }

    /**
     * @param  string|Exception  $message
     * @param  array|null  $errors
     * @return JsonResponse
     */
    public function respondFailedValidation(
        string|Exception $message,
        ?array $errors
    ): JsonResponse {
        return $this->apiResponse(
            [
                'success' => false,
                'message' => $this->morphMessage($message),
                'errors' => $errors ?? null,
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /**
     * @param  string|null  $message
     * @return JsonResponse
     */
    public function respondNoContent(): JsonResponse
    {
        return $this->apiResponse(
            [],
            Response::HTTP_NO_CONTENT
        );
    }

    /**
     * @param  array|Arrayable|JsonSerializable|null  $data
     * @return array|null
     */
    private function morphToArray(array|Arrayable|JsonSerializable|null $data): array|null
    {
        if ($data instanceof Arrayable) {
            return $data->toArray();
        }

        if ($data instanceof JsonSerializable) {
            return $data->jsonSerialize();
        }

        return $data;
    }

    /**
     * @param  string|Exception  $message
     * @return string
     */
    private function morphMessage(string|Exception $message): string
    {
        return $message instanceof Exception
            ? $message->getMessage()
            : $message;
    }

    /**
     * @param  array  $data
     * @param  integer  $code
     * @return JsonResponse
     */
    private function apiResponse(
        array $data,
        int $code = 200
    ): JsonResponse {
        return response()->json($data, $code);
    }
}
