<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use DateTime;
use Firebase\JWT\JWT;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;


trait GeneralServices {

	protected $request;
    public function __construct(Request $request) 
    {
        $this->_client = new Client(['http_errors' => false]);
        // $this->_client = new Client();
        $this->request = $request;
		$this->header = ['User-Token' =>$request->header('User-Token')];
    }

	protected function generateTokenJwt($data) {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $data['key_secret'], // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + (60*60*24)*7,// Expiration time a week
            'data' => $data,

        ];
        return  JWT::encode($payload, env('JWT_SECRET'));        
    }
    public function ResponseJson($status,$message,$data = null){
        $response = [
            'status' => true,
            'message' => $message,
            'data' => $data 
        ];
		if($status != 200){
			$response = [
				'status' => false,
				'message' => $message
			];
		}
		return response()->json($response, $status);
	}
    function ValidateRequest($params,$rules){

		$validator = Validator::make($params, $rules);

		if ($validator->fails()) {
			$response = [
				'status' => false,
				// 'message' => $validator->messages()
				'message' =>  $validator->errors()->first()
			];
			return response()->json($response, 406);
		}
	}   
	protected function POST($url,$data = [],$headers = [],$timeout = ['connection_timeout' => 600,'timeout'=> 600]){
        return json_decode($this->_client->POST($url,[
            'form_params' => $data,
            'headers' => $this->header,
            $timeout
        ])->getBody(),true);
    }
	protected function PUT($url,$data = [],$headers = [],$timeout = ['connection_timeout' => 600,'timeout'=> 600]){
		return json_decode($this->_client->PUT($url,[
			'form_params' => $data,
			'headers' => $this->header,
			$timeout
		])->getBody(),true);
	}

    protected function GET($url,$data = [],$headers = [],$timeout = ['connection_timeout' => 600,'timeout'=> 600]){
          return json_decode($this->_client->GET($url,[
              'form_params' => $data,
              'headers' => $this->header,
              $timeout
          ])->getBody(),true);
    }
	protected function DELETE($url,$data = [],$headers = [],$timeout = ['connection_timeout' => 600,'timeout'=> 600]){
		return json_decode($this->_client->DELETE($url,[
			'form_params' => $data,
			'headers' => $this->header,
			$timeout
		])->getBody(),true);
  }
	
	protected function MULTIPART($url,$general_data = [], $multipart_data = [], $headers = [], $timeout = ['connection_timeout' => 600,'timeout'=> 600]){
		$data = [];
		foreach($general_data as $key => $item){
			if (is_array($item)) {
				foreach($item as $items){
					$data[] = [
						'name'      => $key.'[]',
						'contents'  => $items
					];
				}
			}else{
				$data[] = [
					'name'      => $key,
					'contents'  => $item
				];
			}
		}
		foreach($multipart_data as $key => $file){
			if($file){
				if (is_array($file)) {
					foreach ($file as $row) {
						$data[] = [
							'name'      => $key.'[]',
							'contents'  => fopen($row->getPathname(),'r'),
							'filename'  => $row->getClientOriginalName()
						];
					}
				}else{
					$data[] = [
						'name'      => $key,
						'contents'  => fopen($file->getPathname(),'r'),
						'filename'  => $file->getClientOriginalName()
					];
				}
			}
		}
		return json_decode($this->_client->POST($url,[
			'headers' => $this->header,
			'multipart' => $data,
			$timeout
		])->getBody(),true);
	}


}