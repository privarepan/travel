<?php

namespace App\Traits;

trait ResponseTrait
{
    public function success($data = [], $message = 'success', $code = 1)
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ]);
    }

    public function error($message = 'error',$code = 0, $data = [])
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ]);
    }
}
