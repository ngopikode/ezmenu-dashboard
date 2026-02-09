<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Random\RandomException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

/**
 * Trait ApiResponserTrait
 *
 * Standardized API response structure and error reporting handler for DiGasss.
 *
 * @package App\Traits
 */
trait ApiResponserTrait
{
    /**
     * Return a success JSON response.
     *
     * @param mixed $data The payload to return.
     * @param string $message Custom success message.
     * @param int $code HTTP Status code (default 200).
     * @param array $headers Additional HTTP headers.
     * @return JsonResponse
     */
    protected function successResponse(
        mixed  $data = [],
        string $message = 'Data fetched successfully',
        int    $code = ResponseAlias::HTTP_OK,
        array  $headers = []
    ): JsonResponse
    {
        if (isset($data['wrapper-v2']) && isset($data['headers']) && is_array($data['headers'])) {
            $headers = array_merge($headers, $data['headers']);
            $data = $data['records'];
        }

        return response()->json([
            'success' => true,
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $code)->withHeaders($headers);
    }

    /**
     * Return a client error JSON response (4xx range).
     * Does NOT report to Telegram.
     *
     * @param mixed $errors Validation errors or error details.
     * @param int $code HTTP Status code (default 422).
     * @param string|null $message Custom error message.
     * @return JsonResponse
     */
    protected function failResponse(
        mixed   $errors = [],
        int     $code = ResponseAlias::HTTP_UNPROCESSABLE_ENTITY,
        ?string $message = null
    ): JsonResponse
    {
        return response()->json([
            'success' => false,
            'status' => 'error',
            'message' => $message ?? 'Unprocessable Entity',
            'errors' => $errors
        ], $code);
    }

    /**
     * Return a server error JSON response (5xx range) and report to Telegram.
     *
     * @param mixed $errors The exception object or error array.
     * @param string $message Generic error message for the user.
     * @param int $code HTTP Status code (default 500).
     * @param Request|null $request The current request instance.
     * @return JsonResponse
     * @throws RandomException
     */
    protected function errorResponse(
        mixed   $errors = [],
        string  $message = "Internal Server Error",
        int     $code = ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
        Request $request = null
    ): JsonResponse
    {
        $requestId = $this->reportToTelegram($message, $request, $errors, $code);

        $debugData = ($errors instanceof Throwable) ? [
            'class' => get_class($errors),
            'file' => $errors->getFile(),
            'line' => $errors->getLine(),
            'message' => $errors->getMessage(),
            'trace' => $errors->getTrace() // Optional: remove if payload is too heavy
        ] : $errors;

        $response = [
            'success' => false,
            'status' => 'error',
            'message' => $message,
            'request_id' => $requestId,
        ];

        if (config('app.debug')) {
            $response['debug'] = $debugData;
        }

        return response()->json($response, $code);
    }

    /**
     * Internal helper to send error details to Telegram.
     *
     * @param string $message
     * @param Request|null $request
     * @param mixed $errors
     * @param int $code
     * @return string The generated Request ID.
     * @throws RandomException
     */
    private function reportToTelegram(
        string   $message,
        ?Request $request,
        mixed    $errors,
        int      $code
    ): string
    {
        $requestId = 'ERR-' . Carbon::now()->format('YmdHis') . '-' . bin2hex(random_bytes(4));

        try {
            if (!$request) {
                $request = request();
            }

            $user = $request->user();
            $userInfo = $user ? "ID: $user->id ($user->name)" : "Guest";

            $fileInfo = "";

            if ($errors instanceof Throwable) {
                $errorDetail = $errors->getMessage();
                $fileInfo = "\n📂 <b>File:</b> " . basename($errors->getFile()) . ":{$errors->getLine()}";
            } elseif (is_array($errors) || is_object($errors)) {
                $errorDetail = json_encode($errors);
            } else {
                $errorDetail = (string)$errors;
            }

            $text = "🚨 <b>CRITICAL ERROR REPORT</b> 🚨\n\n" .
                "🆔 <b>Req ID:</b> <code>$requestId</code>\n" .
                "👤 <b>User:</b> $userInfo\n" .
                "🌐 <b>IP:</b> {$request->ip()}\n" .
                "🔗 <b>Method:</b> {$request->method()} {$request->fullUrl()}\n" .
                "🔢 <b>Code:</b> $code\n" .
                "----------------------------\n" .
                "💬 <b>Message:</b> $message\n" .
                "💥 <b>Exception:</b> $errorDetail" .
                $fileInfo;

            $token = config('services.telegram.bot_token');
            $chatId = config('services.telegram.chat_id');

            if ($token && $chatId) {
                Http::timeout(3)->post("https://api.telegram.org/bot$token/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => substr($text, 0, 4090),
                    'parse_mode' => 'HTML',
                    'disable_web_page_preview' => true
                ]);
            }
        } catch (Throwable $e) {
            Log::error("Failed to report to Telegram: " . $e->getMessage());
        }

        return $requestId;
    }
}
