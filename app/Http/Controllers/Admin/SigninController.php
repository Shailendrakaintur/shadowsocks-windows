<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Validator;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Session;
use PDF;
use NumberFormatter;
use Mail;
use Hashids\Hashids;
use Illuminate\Support\Facades\Crypt;
use App\Models\Admin\SigninModel;
use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Hash;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTFactory ;
use view;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class SigninController extends Controller
{

   protected $request;

   public function __construct(Request $request)
   {
        $this->request = $request;
        $this->_signin = new SigninModel();
   }

   public function validator(array $data)
   {
        return validator::make($data,[
        'email' => 'required|email',
        'password' => 'required'
        ]);
   }


   public function signin_doverify(Request $request)
   {
        $error = new MessageBag();
        $validator = $this->validator($request->json()->all());

        if($validator->fails())
        {
            return $validator->errors();
        }


        $data = $request->json()->all();
        $email = $data['email'];
        $password = $data['password'];  

            $credentials = $request->only('email', 'password');
            $check_user_passsword = $this->_signin->check_user_password_matches($email,$password);
           // dd($check_user_passsword);
            if(count($check_user_passsword) > 0)
            {
              
                $payload = JWTFactory::sub($check_user_passsword[0]->Id)
                ->role($check_user_passsword[0]->role)
                ->email($check_user_passsword[0]->email)
                ->make();
               
                $tokendd = JWTAuth::encode($payload)->get();

     

                    $response = array(
                        "email" => $check_user_passsword[0]->email,
                        "role" => $check_user_passsword[0]->role,
                        "Id" => $check_user_passsword[0]->Id,
                        "status" => 1,
                        "token" => $tokendd
                    );
                

            }
            else
            {

                $response = array(
                    "email" => $email,
                    "status" => 0,
                    "message" => "Invalid Credentials"
                );

            }


    
        return $response;
    
    }

    public function token_verify(Request $request)
    {
        $data = $request->json()->all();
        

        $tokenParts = explode(".", $data["token"]);  
        $tokenHeader = base64_decode($tokenParts[0]);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtHeader = json_decode($tokenHeader);
        $jwtPayload = json_decode($tokenPayload);
        if(isset($jwtPayload->email))
        {
            if($data["email"] == $jwtPayload->email)
            {
                return array(
                    "status" => 1,
                    "verification" => "pass"
                );
            }
            else
            {
                return array(
                    "status" => 0,
                    "verification" => "fail"
                );
            }
        }
        else
        {
            return array(
                "status" => 0,
                "verification" => "fail"
            );
        }
    }

}