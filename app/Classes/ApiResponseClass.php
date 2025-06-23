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

    public static function rollback($e, $message = 'An error occurred while processing your request.')
    {
        DB::rollBack();
        self::throwException($e, $message);
    }

    public static function throwException($e, $message = 'An error occurred while processing your request.')
    {
        Log::info($e);
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => $message,
                'errors' => $e->getMessage()
            ], 500)
        );
    }

    public static function sendResponse($data, $message = 'Request processed successfully.')
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], 200);
    }
}
