<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    // use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function base_response($code, $message, $data = null){
        return response()->json([
            "code"    => $code,
            "message" => $message,
            "data"    => $data
        ])->withHeaders([
            'Content-Type' => "application/json"
        ]);
    }
}
