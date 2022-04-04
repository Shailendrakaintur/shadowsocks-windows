<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class UserModel extends Model
{
    use HasFactory;

    protected $table="users";
    protected $primaryKey = "uid";
    protected $fillable = ["email","password"];

    public function check_email($email)
    {
        $v_email = DB::table('users')->where('email',$email)->where('isDeleted',0)->count();
        return $v_email;
    }
    
    public function create_user($data_array)
    {
        $user = DB::table('users')->insertGetId($data_array);
        return $user;
    }



    public function check_user_password_matches($id,$password)
    {

        $user = UserModel::where('email',$id)->where('password',base64_encode(hash('sha256', $password)))->get();
        if(count($user) > 0)
        {
           
            return $user;
        }
        else
        {
            return [];
        }
       
    }

    public function wishlist($data_array)
    {
        $user = DB::table('wishlist')->insertGetId($data_array);
        return $user;
    }
    public function list_wishlist($userid)
    {
        $user = DB::table('wishlist')->where("userid",$userid)->get();
        return $user;
    }
    public function delete_wishlist($userid,$productid)
    {
        $user = DB::table('wishlist')->where("productid",$productid)->where("userid",$userid)->delete();
        return $user;
    }

    public function verifycode($data)
    {
        $user = DB::table('users')->where("email",$data["email"])->where("verification_code",$data["code"])->get();
        return count($user);
    }

    public function check_appointment($data_array)
    {
        $check = DB::table('appointment')->whereDate('date', '=', $data_array["date"])
        ->whereTime('from_time', '>=', $data_array["from_time"]) 
        ->whereTime('to_time', '<=', $data_array["to_time"]);
 
        
        return $check->count();
    }

    public function appointment($data_array)
    {
        $user = DB::table('appointment')->insertGetId($data_array);
        return $user;
    }
}
