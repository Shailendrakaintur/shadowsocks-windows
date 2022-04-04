<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User\UserModel;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Hash;
use DB;
use Mail;
use Validator;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use App\Mail\verification;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTFactory ;
use view;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use DateTime;
class UserController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
         $this->request = $request;
         $this->_user = new UserModel();
    }

    
    public function create(Request $request)
    {
        
        date_default_timezone_set('Asia/Kolkata');
        $cdate = date('Y-m-d H:i:s');
        $vcode = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
        $data = $request->json()->all();
        $check_email = $this->_user->check_email($data["email"]);
        if($check_email == 0)
        {
            $data["verification_code"] = $vcode;
            $data["created_at"] = $cdate;
            $data["updated_at"] = $cdate;
            $data["isDeleted"] = 0;
            $data["verified"] = 0;
            $data['password'] = base64_encode(hash('sha256', $data['password']));
            $dump = $this->_user->create_user($data);
            if($dump)
            {
                $name = $data["name"];
                $email = $data["email"];
                $data = ['message' => "Your verification code is $vcode",'subject' => "Beumont Admin",'greetings' => "Hello $name ,"];
                $send = Mail::to($email)->send(new verification($data));  
                
                if(count(Mail::failures()) > 0 ) 
                {  
                    $response = array(
                        "status" => 1,
                        "message" => "Account Created Successfully",
                        "Verification" => "Failed to send code."
                    );
                }
                else
                {
                    $response = array(
                        "status" => 1,
                        "message" => "Account Created Successfully",
                        "Verification" => "Verfication code has been sent to" .$email
                    );
                }
    
    
    
            }
            else
            {
                $response = array(
                    "status" => 0,
                    "message" => "Error Please try again later"
                );
            }

        }
        else
        {
            $response = array(
                "status" => 0,
                "message" => "Email address is already taken"
            );
        }
        
      

        return $response;
    }


    public function validator(array $data)
    {
         return validator::make($data,[
         'email' => 'required|email',
         'password' => 'required'
         ]);
    }


    public function login(Request $request)
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
            $check_user_passsword = $this->_user->check_user_password_matches($email,$password);
            if(count($check_user_passsword) > 0)
            {
                if($check_user_passsword[0]->verified == 0)
                {

                    $vcode = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
                    
                    $update_data["verification_code"] = $vcode;
                    
                    $user = DB::table('users')->where("email",$check_user_passsword[0]->email)->update($update_data);
                    if($user)
                    {
                    $name = $check_user_passsword[0]->first_name;
                    $email = $check_user_passsword[0]->email;
                    $data = ['message' => "Your verification code is $vcode",'subject' => "Beumont Admin",'greetings' => "Hello $name ,"];
                    $send = Mail::to($email)->send(new verification($data));  
                    }

                    

                    $response = array(
                        "status" => 0,
                        "email" => $check_user_passsword[0]->email,
                        "message" => "Account is not verified"
                    );
                }
                else
                {
                        $payload = JWTFactory::sub($check_user_passsword[0]->uid)
                        ->role("customer")
                        ->email($check_user_passsword[0]->email)
                        ->make();
                    
                        $tokendd = JWTAuth::encode($payload)->get();

            

                            $response = array(
                                "email" => $check_user_passsword[0]->email,
                                "id" => $check_user_passsword[0]->uid,
                                "status" => 1,
                                "token" => $tokendd
                            );
                 }
                

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
 
     public function add_wishlist(Request $request)
     {
         $data = $request->json()->all();
         $add = $this->_user->wishlist($data);
         if($add)
         {
             $response = array(
                 "status" => 1,
                 "message" => "Added Successfully"
             );
         }
         else
         {
             $response = array(
                 "status" => 0,
                 "message" => "Failed"
             );  
         }
 
         return $response;
 
     }

     public function list_wishlist(Request $request)
     {
         $userid = $request->header('userid');
         $data = $request->json()->all();
         $list = $this->_user->list_wishlist($userid);
         if($list)
         {
             $response = array(
                 "status" => 1,
                 "wishlist" => $list
             );
         }
         else
         {
             $response = array(
                 "status" => 0,
                 "message" => "Failed"
             );  
         }
 
         return $response;
 
     }


     public function delete_wishlist(Request $request)
     {
         $userid = $request->header('userid');
         $data = $request->json()->all();
         $remove = $this->_user->delete_wishlist($userid);
         if($remove)
         {
             $response = array(
                 "status" => 1,
                 "message" => "Removed Successsfully"
             );
         }
         else
         {
             $response = array(
                 "status" => 0,
                 "message" => "Failed"
             );  
         }
 
         return $response;
 
     }

     public function verify_code(Request $request)
     {

         $data = $request->json()->all();
         $veriffy = $this->_user->verifycode($data);
         if($veriffy > 0)
         {
             $data_update["verified"] = 1; 
             $user = DB::table('users')->where("email",$data["email"])->update($data_update);
                   
             $response = array(
                 "status" => 1,
                 "message" => "Verified Successsfully"
             );
         }
         else
         {
             $response = array(
                 "status" => 0,
                 "message" => "Failed"
             );  
         }
 
         return $response;
 
     }


     public function add_appointment(Request $request)
     {
         $data = $request->json()->all();
         $data["created_date"] = now();
         $check = $this->_user->check_appointment($data);
         if($check == 0)
         {
                $add = $this->_user->appointment($data);
                if($add)
                {
                    $response = array(
                        "status" => 1,
                        "message" => "Appointment Created Successfully"
                    );
                }
                else
                {
                    $response = array(
                        "status" => 0,
                        "message" => "Failed"
                    );  
                }
         }
         else
         {
            $response = array(
                "status" => 0,
                "message" => "Appointment is not available at your date and timing"
            );  
         }

         return $response;
     }
}
