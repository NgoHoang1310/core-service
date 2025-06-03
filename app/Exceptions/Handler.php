<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Nette\Schema\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Báo cáo lỗi nếu cần
        });

        $this->renderable(function (Throwable $e, $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return $this->handleApiException($e, $request);
            }
        });
    }

    /**
     * Xử lý API exception
     */
    private function handleApiException(Throwable $exception, $request)
    {
        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'success' => false,
                'message' => 'Chưa xác thực',
                'status' => 401
            ], 401);
        }

        if ($exception instanceof ValidationException) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $exception->getMessage(),
                'status' => 422
            ], 422);
        }

        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy dữ liệu',
                'status' => 404
            ], 404);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'success' => false,
                'message' => 'Endpoint không tồn tại',
                'status' => 404
            ], 404);
        }

        // Các trường hợp khác, trả về lỗi server
        $statusCode = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500;
        $message = config('app.debug') ? $exception->getMessage() : 'Lỗi máy chủ';

        return response()->json([
            'success' => false,
            'message' => $message,
            'status' => $statusCode
        ], $statusCode);
    }
}
