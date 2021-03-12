<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\GeneralServices;

class UserController extends Controller
{
    use GeneralServices;
    public function register(Request $request){
        $postParam = $request->only([
            'fullname',
            'email',
            'password',
            'password_confirmation',
            'gender'
        ]);
        if ($request->hasFile('photo')){
            $image = $request->file('photo');
            $name = rand(100000,1001238912).'.'.$image->getClientOriginalExtension();
            $request->file('photo')->move('image/user/', $name);
            $postParam['photo'] = $name;
        }

        $response = $this->MULTIPART(env('AUTH_URL').'api/user/register', $postParam);
        if($response['status'] == false){
			return response()->json($response, 406);
        }
        return $response;
    }
    public function login(Request $request){
        $postParam = $request->only([
            'email',
            'password',
        ]);

        $response = $this->POST(env('AUTH_URL').'api/user/login', $postParam);
        if($response['status'] == false){
			return response()->json($response, 406);
        }
        $response['data']['photo_url'] = url('/')."/image/user/".$response['data']['photo'];

        return $response;
    }
    public function logout(Request $request){
        $response = $this->GET(env('AUTH_URL').'api/user/logout');
        if($response['status'] == false){
			return response()->json($response, 406);
        }

        return $response;
    }
}
