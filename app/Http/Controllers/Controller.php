<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function jsonResponse($data, $code = 200) {
        return response()->json($data, $code);
    }
}
