<?php

namespace App\Traits;

trait ApiResponseTrait
{
    /**
     * Trả về phản hồi thành công
     *
     * @param mixed $result
     * @param string|null $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse($result, $message = null, $code = 200)
    {
        return response()->json([
            'success' => true,
            'status' => $code,
            'message' => $message,
            'result' => $result
        ], $code);
    }

    /**
     * Trả về phản hồi lỗi
     *
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse($message, $code)
    {
        return response()->json([
            'status' => $code,
            'success' => false,
            'message' => $message
        ], $code);
    }
}
