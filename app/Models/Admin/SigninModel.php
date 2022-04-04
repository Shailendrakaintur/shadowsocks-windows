<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use DB;

class SigninModel extends Model
{
    use HasFactory;
    protected $table="superadmin";
    protected $primaryKey = "id";
    protected $fillable = ["email","password"];




    public function check_user_or_email_exists($email)
    {
        
        $user = SigninModel::where('email',$email)->get();
        return $user;
    }


    public function check_user_password_matches($id,$password)
    {

        $user = SigninModel::where('email',$id)->where('password',base64_encode(hash('sha256', $password)))->get();
        if(count($user) > 0)
        {
           
            $user[0]->Id = "999";
            return $user;
        }
        else
        {
            return [];
        }
      
        
       
    }
}
