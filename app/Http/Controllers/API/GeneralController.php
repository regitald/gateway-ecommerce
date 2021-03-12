<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\GeneralServices;

class GeneralController extends Controller
{
    use GeneralServices;

    public function shipping(){
        $response = $this->GET(env('ORDER_URL').'api/shipping');
        if($response['status'] == false){
			return response()->json($response, 406);
        }
    
        return $response;
    }
    public function payment(){
        $response = $this->GET(env('ORDER_URL').'api/payment');
        if($response['status'] == false){
			return response()->json($response, 406);
        }
    
        return $response;
    }
}
