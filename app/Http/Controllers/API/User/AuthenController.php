<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenController extends Controller
{
    use GeneralTrait;

    public function login(Request $request){
        try {

            //validation
            $rules = [
                "email" => "required|exists:users,email",
                "password" => "required"
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }


            // login
            $credentioal = $request -> only(['email' ,'password']);

            //check if email and password exist in  admin and return token (contain all data)
            $token = Auth::guard('user_api')->attempt($credentioal);  // create token contain email , password

            if(!$token){
                return $this->returnError('E001' , 'Data Not Correct');
            }

            $user = Auth::guard('user_api')->user(); //  get data of admin table
            $user -> api_token = $token;     // add key api_token to user
            return $this->returnData('user' , $user);


        }catch (\Exception $ex){
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }


    public function logout(Request $request){
        //to get auth token form user / postman
        $token = $request->auth_token;

        if($token){
            try {
                JWTAuth::setToken($token)->invalidate(); // logout
            }catch(\Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
                return $this->returnError('','something went wrong');
            }

            return $this->returnSuccessMessage('logout successfully');

        }else{
            return $this->returnError('1110', 'something went wrong');
        }

    }
}
