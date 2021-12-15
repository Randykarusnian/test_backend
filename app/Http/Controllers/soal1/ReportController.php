<?php

namespace App\Http\Controllers\soal1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\Transaction;
use App\Models\Merchant;
use App\Models\Outlet;
use DB;

class ReportController extends Controller
{
    //SOAL 1C
    public function reportMerchant(Request $request){
        $user = JWTAuth::parseToken()->authenticate();
        if (!isset($request->limit)) {
            $limit = 10;
        }else{
            $limit = $request->limit;
        }
        if (!isset($request->offset)) {
            $offset = 0;
        }else{
            $offset = $request->offset;
        }

        if (!isset($request->merchant_id)) {
            $merchant = Merchant::where('user_id', $user->id)->first();
        }else{
            $merchant = Merchant::where('id', $request->merchant_id)->first();
            if (!$merchant) {
                return $this->base_response("02", "Merchant tidak ditemukan");
            }else if ($merchant->user_id != $user->id) {
                return $this->base_response("02", "Hanya bisa mengakses merchant sendiri");
            }
        }
        
        $report = DB::select('SELECT date(transactions.created_at) as tanggal, SUM(transactions.bill_total) as total  FROM transactions JOIN merchants ON transactions.merchant_id = merchants.id WHERE transactions.merchant_id = '.$merchant->id.' GROUP BY date(transactions.created_at) ORDER BY date(transactions.created_at)');
        $dataReport = [];
        if ($report) {
            
            $date = $this->dateRange('2021-11-01', '2021-11-30');
            $dummy = [];

            for ($i=0; $i < count($date); $i++) { 
               $dummy[$i]['tanggal'] = $date[$i];
               $dummy[$i]['merchant'] = $merchant->merchant_name;
               $dummy[$i]['total'] = 0;

               for ($k=0; $k < count($report); $k++) { 
                   if (in_array($report[$k]->tanggal, $date)) {
                        $dummy[$k]['total'] = $report[$k]->total;
                   }
               }
            }
            $count = $offset+$limit;
            if ($count > 30) {
                $count = 30;
            }
            for ($i=$offset; $i < $count; $i++) { 
                array_push($dataReport, $dummy[$i]);
            }
            return $this->base_response("00", "Data Ditemukan", $dataReport);
        }else{
            return $this->base_response("02", "Data Tidak Ditemukan", $report);
        }
    }

    //SOAL 1D
    public function reportOutlet(Request $request){
        $user = JWTAuth::parseToken()->authenticate();
        if (!isset($request->limit)) {
            $limit = 10;
        }else{
            $limit = $request->limit;
        }
        if (!isset($request->offset)) {
            $offset = 0;
        }else{
            $offset = $request->offset;
        }

        if (!isset($request->outlet_id)) {
            return $this->base_response("-1", "Missing key : outlet_id");
        }else{
            $outlet = Outlet::where('id', $request->outlet_id)->first();
            $merchant = Merchant::where('user_id', $user->id)->first();
            if (!$outlet) {
                return $this->base_response("02", "Outlet tidak ditemukan");
            }else if ($outlet->merchant_id != $merchant->id) {
                return $this->base_response("02", "Hanya bisa mengakses outlet sendiri");
            }
        }
        
        $report = DB::select('SELECT date(transactions.created_at) as tanggal, SUM(transactions.bill_total) as total  FROM transactions JOIN merchants ON transactions.merchant_id = merchants.id JOIN outlets ON transactions.outlet_id = outlets.id WHERE transactions.outlet_id = '.$outlet->id.' GROUP BY date(transactions.created_at) ORDER BY date(transactions.created_at)');
        $dataReport = [];
        if ($report) {
            
            $date = $this->dateRange('2021-11-01', '2021-11-30');
            $dummy = [];

            for ($i=0; $i < count($date); $i++) { 
               $dummy[$i]['tanggal'] = $date[$i];
               $dummy[$i]['merchant'] = $merchant->merchant_name;
               $dummy[$i]['outlet'] = $outlet->outlet_name;
               $dummy[$i]['total'] = 0;

               for ($k=0; $k < count($report); $k++) { 
                   if (in_array($report[$k]->tanggal, $date)) {
                        $dummy[$k]['total'] = $report[$k]->total;
                   }
               }
            }
            $count = $offset+$limit;
            if ($count > 30) {
                $count = 30;
            }
            for ($i=$offset; $i < $count; $i++) { 
                array_push($dataReport, $dummy[$i]);
            }
            return $this->base_response("00", "Data Ditemukan", $dataReport);
        }else{
            return $this->base_response("02", "Data Tidak Ditemukan", $report);
        }
    }

    function dateRange( $first, $last, $step = '+1 day', $format = 'Y-m-d' ) {

        $dates = array();
        $current = strtotime( $first );
        $last = strtotime( $last );
    
        while( $current <= $last ) {
    
            $dates[] = date( $format, $current );
            $current = strtotime( $step, $current );
        }
    
        return $dates;
    }
}
