<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\GeneralServices;

class ProductController extends Controller
{
    use GeneralServices;
    public function index(Request $request){
        $postParam = $request->only([
            'category_id',
            'name',
        ]);

        $response = $this->GET(env('PRODUCT_URL').'api/product?category_id='.$request->category_id.'&name='.$request->name);
        if($response['status'] == false){
			return response()->json($response, 406);
        }

        return $response;
    }
    public function show($id){
        $response = $this->GET(env('PRODUCT_URL').'api/product/'.$id);
        if($response['status'] == false){
			return response()->json($response, 406);
        }
        return $response;
    }
    public function category(Request $request){
        $postParam = $request->only([
            'category_id',
            'name',
        ]);

        $response = $this->GET(env('PRODUCT_URL').'api/category?category_id='.$request->category_id.'&name='.$request->name);
        if($response['status'] == false){
			return response()->json($response, 406);
        }
        $response['data'] = collect($response['data'])->map(function($key){
            $key['logo_url']  = url('/')."/image/category/".$key['logo'];
            return $key;
		});
        return $response;
    }
    public function categoryShow($id){
        $response = $this->GET(env('PRODUCT_URL').'api/category/'.$id);
        if($response['status'] == false){
			return response()->json($response, 406);
        }
        
        $response['data']['logo_url']  = url('/')."/image/category/".$response['data']['logo'];
        return $response;
    }
}
