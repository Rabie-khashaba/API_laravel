<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Category;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthenController extends Controller
{

    use GeneralTrait;

    public function login(Request $request){
        try {

            //validation
            $rules = [
                "email" => "required|exists:admins,email",
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
            $token = Auth::guard('admin_api')->attempt($credentioal);  // token contain email , password

            if(!$token){
                return $this->returnError('E001' , 'Data Not Correct');
            }

            $admin = Auth::guard('admin_api')->user(); //  get data of admin table
            $admin -> api_token = $token;     // add key api_token to admin
            return $this->returnData('user' , $admin);


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

