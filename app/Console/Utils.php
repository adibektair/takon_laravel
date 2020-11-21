<?php


namespace App\Console;

class Utils
{
    public function makeResponse(int $code, Bool $success, Array $other)
    {
        $json = array_merge($other, ['success' => $success]);
        return \response()->json($json)->setStatusCode($code);
    }
}
