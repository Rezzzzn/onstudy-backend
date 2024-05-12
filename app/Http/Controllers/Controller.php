<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function jsonResponse(array $data, $code = 200) {
        return response()->json($data, $code);
    }
}
