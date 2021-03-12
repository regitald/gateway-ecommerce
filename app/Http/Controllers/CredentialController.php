<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\GeneralServices;

class CredentialController extends Controller
{
    use GeneralServices;

	public function index(Request $request){
        $rules = [
			'user' => 'required|string',
			'key_secret' => 'required|string'
		];

		$validateData = $this->ValidateRequest($request->all(), $rules);

		if (!empty($validateData)) {
			return $validateData;
		}
        if($request->user != env('user') || $request->key_secret != env('key_secret')){


            return $this->ResponseJson(406,"Invalid Auth User or Key Secret");
        }

		$data['token'] = $this->generateTokenJwt($request->all());

        return $this->ResponseJson(200,"Token",$data);
	}
}
