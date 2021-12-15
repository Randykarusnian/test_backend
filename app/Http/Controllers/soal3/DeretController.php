<?php

namespace App\Http\Controllers\soal3;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeretController extends Controller
{
    public function deret(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                "deret1" => "required",
                "deret2" => "required",
                "length" => "required",
            ], [
                "deret1.required" => "Missing key : deret1",
                "deret2.required" => "Missing key : deret2",
                "length.required" => "Missing key : length",
            ]
            );
    
            if ($validator->fails()) {
                return $this->base_response("-1", $validator->errors()->first());
            }
    
            $rumus = (int)$request->deret2 - (int)$request->deret1;
            $angka = [];
            $hasil = (int)$request->deret1;
            array_push($angka, $hasil);
            for ($i=1; $i < $request->length; $i++) { 
                $hasil = $hasil + $rumus;
                array_push($angka, $hasil);
            }

            $data = implode(',', $angka);

            return $this->base_response("00", "Berhasil mengambil data", $data);
        } catch (Exception $th) {
            return $this->base_response("02", "Gagal mengambil data", $th->getMessage());

        }
    }
}
