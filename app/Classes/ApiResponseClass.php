<?php

namespace App\Classes;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
class ApiResponseClass
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function rollback($e, $message = 'An error occurred while processing your request.', $code = 500)
    {
        DB::rollBack();
        self::throwException($e, $message, $code);
    }

    public static function throwException($e, $message = 'An error occurred while processing your request.', $code = 500)
    {
        Log::info($e);
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => $message,
                'errors' => $e->getMessage()
            ], $code)
        );
    }

    public static function sendResponse($data, $message = 'Request processed successfully.', $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
