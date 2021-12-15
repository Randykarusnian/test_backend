<?php

namespace App\Http\Controllers\soal4;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SortController extends Controller
{
    public function sort(){
        $array =  [4, -7, -5, 3, 3.3, 9, 0, 10, 0.2];

        $asc = [];
        sort($array);
        array_push($asc, $array);

        $desc = [];
        rsort($array);
        array_push($desc, $array);

        $data = [
            "asc" => $asc,
            "desc" => $desc,
        ];

        return $this->base_response("00", "Berhasil sort data", $data);
    }
}
