<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\GeneralServices;

class OrderController extends Controller
{
    use GeneralServices;
    public function index(Request $request){
        $postParam = $request->only([
            'is_history',
            'member_id',
        ]);

        $response = $this->GET(env('ORDER_URL').'api/cart?member_id='.$request->member_id.'&is_history='.$request->is_history);
        if($response['status'] == false){
			return response()->json($response, 406);
        }
        if($request->is_history=='false'){
            $makeResponse['member_id'] = $request->member_id;
            $makeResponse['order_id'] =  $response['data']['order_id'];
            $makeResponse['order_code'] =  $response['data']['order_code'];
            $makeResponse['order_total_price'] =  $response['data']['order_total_price'];
            $makeResponse['order_status'] = "Placed";

            $response['data']['details'] = collect($response['data']['details'])->map(function($key){
                $product_detail = $this->GET(env('PRODUCT_URL').'api/product/'.$key['product_id']);
                $key['product_data']  = null;
                if($product_detail['status']==true){
                    $key['product_data']  = $product_detail['data'];
                }
                return $key;
            });

            $makeResponse['items'] = $response['data']['details'];
            $response['data'] = $makeResponse;
        }

        return $response;
    }
    public function store(Request $request){
        $postParam = $request->only([
            'member_id',
            'product_id',
            'qty',
            'price',
            'special_instructions'
        ]);
        
        $check_product = $this->GET(env('PRODUCT_URL').'api/product/'.$request['product_id']);
        if($check_product['status'] == false){
			return response()->json($check_product, 406);
        }
        $response = $this->POST(env('ORDER_URL').'api/cart', $postParam);
        if($response['status'] == false){
			return response()->json($response, 406);
        }
        return $response;
    }
    public function updateQty(Request $request){
        $postParam = $request->only([
            'order_detail_id',
            'product_id',
            'qty',
            'price'
        ]);
        $response = $this->PUT(env('ORDER_URL').'api/cart/update', $postParam);
        if($response['status'] == false){
			return response()->json($response, 406);
        }
        return $response;
    }
    public function delete(Request $request){
        $postParam = $request->only([
            'order_detail_id'
        ]);
        $response = $this->DELETE(env('ORDER_URL').'api/cart/delete?order_detail_id='.$postParam->order_detail_id);
        if($response['status'] == false){
			return response()->json($response, 406);
        }
        return $response;
    }
    public function checkout(Request $request){
        $postParam = $request->only([
            'member_id',
            'member_email',
            'member_address',
            'payment_method_id',
            'shipping_id',
            'distance',
            'attachment'
        ]);
        
        if ($request->hasFile('attachment')){
            $image = $request->file('attachment');
            $name = rand(100000,1001238912).'.'.$image->getClientOriginalExtension();
            $request->file('attachment')->move('image/order/', $name);
            $postParam['attachment'] = $name;
        }

        $response = $this->MULTIPART(env('ORDER_URL').'api/checkout', $postParam);
        if($response['status'] == false){
			return response()->json($response, 406);
        }
        $makeResponse['member_id'] = $request->member_id;
        $makeResponse['order_id'] =  $response['data']['order_id'];
        $makeResponse['order_code'] =  $response['data']['order_code'];
        $makeResponse['attachment'] =  url('/')."/image/order/".$response['data']['attachment'];
        $makeResponse['order_total_price'] =  $response['data']['order_total_price'];
        $makeResponse['order_status'] = "Success";
        if($response['data']['order_status'] != '1')
            $makeResponse['order_status'] = "Failed";

        $response['data']['details'] = collect($response['data']['details'])->map(function($key){
            $product_detail = $this->GET(env('PRODUCT_URL').'api/product/'.$key['product_id']);
            $key['product_data']  = null;
            if($product_detail['status']==true){
                $key['product_data']  = $product_detail['data'];
            }
            return $key;
        });
        
        $makeResponse['payment_method'] =  $response['data']['payment_method'];
        $makeResponse['shipping_method'] =  $response['data']['shipping_method'];

        $makeResponse['items'] = $response['data']['details'];
        $response['data'] = $makeResponse;
        return $response;
    }

}
