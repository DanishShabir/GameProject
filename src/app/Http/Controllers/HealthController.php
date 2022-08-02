<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use Auth;
use Log;
use DB;


class HealthController extends Controller
{
    public function healthCheck()
    {
        $pdo = DB::connection()->getPdo();

        if($pdo)
        {
            //echo "Connected successfully to database ".DB::connection()->getDatabaseName();
             // return http 200
            return response()->json([
                'status' => "OK",
            ], 200);
        } else {
            //echo "You are not connected to database";
             // return http 500
            return response()->json([
                'status' => "BAD",
            ], 500);
        }
    }
}
