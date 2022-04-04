<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\MasterModel;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Hash;
use DB;
use Validator;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Aws\S3\S3Client;
use Aws\S3\S3ClientInterface;
use DateTime;

use PDF;
use NumberFormatter;
use Mail;
use Hashids\Hashids;
use Illuminate\Support\Facades\Crypt;
use App\Models\Admin\ActivityModel;
use App\Mail\ForgotPasswordMail;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTFactory ;
use view;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class MasterController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
         $this->request = $request;
         $this->_master = new MasterModel();
    }

    public function validator(array $data)
    {
         return validator::make($data,[
         'title' => 'required'
         ]);
    }

    public function add_brands(Request $request)
    {
         $error = new MessageBag();
         $validator = $this->validator($request->json()->all());
 
   
         $data = $request->json()->all();

         $data["bussiness_details_select_all_countries"] = implode('||',$data["bussiness_details_select_all_countries"]);
         $data["isDeleted"] = 0;
         $dump = $this->_master->insert_brands($data);
         
         if($dump)
         {
            $ldata["type"] = "all";
            $list = $this->_master->brand_list($ldata);
        
            $response = array(
                "status" => 1,
                "message" => "Brand Added Successfully",
                "list" => $list
            );
         }
         else
         {

            $response = array(
                "status" => 0,
                "message" => "Try Again Later"
            );
         }

         return $response;
    }


    public function list_brands(Request $request)
    {
         $data = $request->json()->all();
        
         $list = $this->_master->brand_list($data);
         if($list)
         {
            $response = array(
                "status" => 1,
                "data" => $list
            );
         }
         else
         {

            $response = array(
                "status" => 0,
                "message" => "Try Again Later"
            );
         }

         return $response;
    }






    public function delete_brands(Request $request)
    {
         $brand = $request->header('brandId');
         $data = $request->json()->all();
         $data_array["isDeleted"] = "1";
         $delete = $this->_master->brand_delete($brand,$data_array);
         if($delete)
         {
            $list = $this->_master->brand_list($data);
            $response = array(
                "status" => 1,
                "message" => "Deleted Successfully",
                "list" => $list
            );
         }
         else
         {

            $response = array(
                "status" => 0,
                "message" => "Error"
            );
         }

         return $response;
    }


    public function edit_brands(Request $request)
    {
         $brand = $request->header('brandId');

         $data = $request->json()->all();
         $delete = $this->_master->brand_delete($brand,$data);
         if($delete)
         {
             
            $response = array(
                "status" => 1,
                "message" => "Updated Successfully"
            );
         }
         else
         {

            $response = array(
                "status" => 0,
                "message" => "Error"
            );
         }

         return $response;
    }




    public function add_cat1(Request $request)
    {
         $error = new MessageBag();
         $validator = $this->validator($request->json()->all());
 
         if($validator->fails())
         {
             return $validator->errors();
         }
         $data = $request->json()->all();
         $data["isDeleted"] = 0;
         $dump = $this->_master->insert_cat1($data);
         if($dump)
         {
            $response = array(
                "status" => 1,
                "message" => "Category Level 1 Added Successfully"
            );
         }
         else
         {

            $response = array(
                "status" => 0,
                "message" => "Try Again Later"
            );
         }

         return $response;
    }


    public function list_cat1(Request $request)
    {
         $list = $this->_master->cat1_list();
         if($list)
         { 
            $response = array(
                "status" => 1,
                "data" => $list
            );
         }
         else  
         {

            $response = array(
                "status" => 0,
                "message" => "Try Again Later"
            );
         }

         return $response;
    }



    public function delete_cat1(Request $request)
    {
         $brand = $request->header('cat1');

         $data_array["isDeleted"] = "1";
         $delete = $this->_master->cat1_delete($brand,$data_array);
         if($delete)
         {
            $list = $this->_master->cat1_list();
            $response = array(
                "status" => 1,
                "message" => "Deleted Successfully",
                "list" => $list
            );
         }
         else
         {

            $response = array(
                "status" => 0,
                "message" => "Error"
            );
         }

         return $response;
    }


    public function edit_cat1(Request $request)
    {
         $brand = $request->header('cat1');
         $data = $request->json()->all();
         
         $delete = $this->_master->cat1_delete($brand,$data);
         if($delete)
         {
            $response = array(
                "status" => 1,
                "message" => "Updated Successfully"
            );
         }
         else
         {

            $response = array(
                "status" => 0,
                "message" => "Error"
            );
         }

         return $response;
    }



    public function add_cat2(Request $request)
    {
         $error = new MessageBag();
         $validator = $this->validator($request->json()->all());
         $cat1 = $request->header('cat1');
         if($validator->fails())
         {
             return $validator->errors();
         }
         $data = $request->json()->all();
         $data["cat1id"] = $cat1;
         $data["isDeleted"] = 0;
         $dump = $this->_master->insert_cat2($data);
         if($dump)
         {
            $response = array(
                "status" => 1,
                "message" => "Category Level 2 Added Successfully"
            );
         }
         else
         {

            $response = array(
                "status" => 0,
                "message" => "Try Again Later"
            );
         }

         return $response;
    }


    public function list_cat2(Request $request)
    {
         $list = $this->_master->cat2_list();
         if($list)
         {
            $response = array(
                "status" => 1,
                "data" => $list
            );
         }
         else
         {

            $response = array(
                "status" => 0,
                "message" => "Try Again Later"
            );
         }

         return $response;
    }



    public function delete_cat2(Request $request)
    {
         $brand = $request->header('cat2');

         $data_array["isDeleted"] = "1";
         $delete = $this->_master->cat2_delete($brand,$data_array);
         if($delete)
         {
            $list = $this->_master->cat2_list();
            $response = array(
                "status" => 1,
                "message" => "Deleted Successfully",
                "list" => $list
            );
         }
         else
         {

            $response = array(
                "status" => 0,
                "message" => "Error"
            );
         }

         return $response;
    }


    public function edit_cat2(Request $request)
    {
         $brand = $request->header('cat2');
         $data = $request->json()->all();
         
         $delete = $this->_master->cat2_delete($brand,$data);
         if($delete)
         {
            $response = array(
                "status" => 1,
                "message" => "Updated Successfully"
            );
         }
         else
         {

            $response = array(
                "status" => 0,
                "message" => "Error"
            );
         }

         return $response;
    }


///////////////////////////////////////////////////////////////////////////////////////////////////////////

        public function add_variation(Request $request)
        {

            $data = $request->json()->all();
            $data["isDeleted"] = 0;
            $dump = $this->_master->insert_variation($data);
            if($dump)
            {
                if($dump == 999)
                {
                
                    
                    $response = array(
                        "status" => 0,
                        "message" => "Variation already exists",
                    );
                }
                else
                {

                
                    $added = $this->_master->variation_list_added($dump);
                    $response = array(
                        "status" => 1,
                        "message" => "Variation Added Successfully",
                        "variation" => $added
                    );
                }
            }
            else
            {

                $response = array(
                    "status" => 0,
                    "message" => "Try Again Later"
                );
            }

            return $response;
        }


        public function list_variation(Request $request)
        {
            $list = $this->_master->variation_list();
            if($list)
            {
                $response = array(
                    "status" => 1,
                    "data" => $list
                );
            }
            else
            {

                $response = array(
                    "status" => 0,
                    "message" => "Try Again Later"
                );
            }

            return $response;
        }






        public function delete_variation(Request $request)
        {
            $vid = $request->header('variationid');
    
            $data_array["isDeleted"] = "1";
            $delete = $this->_master->variation_delete($vid,$data_array);
            if($delete)
            {
                $response = array(
                    "status" => 1,
                    "message" => "Deleted Successfully"
                );
            }
            else
            {

                $response = array(
                    "status" => 0,
                    "message" => "Error"
                );
            }

            return $response;
        }


        public function edit_variation(Request $request)
        {
            $vid = $request->header('variationid');
            $data = $request->json()->all();
            
            $delete = $this->_master->variation_delete($vid,$data);
            if($delete)
            {
                $response = array(
                    "status" => 1,
                    "message" => "Updated Successfully"
                );
            }
            else
            {

                $response = array(
                    "status" => 0,
                    "message" => "Error"
                );
            }

            return $response;
        }















        public function add_variation_values(Request $request)
        {

            $variableId = $request->header('variationid');
            $data = $request->json()->all();
            $data_array["variationid"] = $variableId;
            $data_array["variation_value"] = $data["name"];
            $data_array["IsDeleted"] = 0;
            $dump = $this->_master->insert_variation_value($data_array);
            if($dump)
            {
                $added = $this->_master->variation_list_values_added($dump);
                $response = array(
                    "status" => 1,
                    "message" => "Variation Value Added Successfully",
                    "variationValue" => $added
                );
            }
            else
            {

                $response = array(
                    "status" => 0,
                    "message" => "Try Again Later"
                );
            }

            return $response;
        }




        public function list_variation_values(Request $request)
        {
            $variableId = $request->header('variationid');
            $list = $this->_master->variation_list_values($variableId);
            if($list)
            {
                $response = array(
                    "status" => 1,
                    "data" => $list
                );
            }
            else
            {

                $response = array(
                    "status" => 0,
                    "message" => "Try Again Later"
                );
            }

            return $response;
        }



        
        public function delete_variation_values(Request $request)
        {
            $vid = $request->header('variationvalueid');
    
            $data_array["isDeleted"] = "1";
            $delete = $this->_master->variation_value_delete($vid,$data_array);
            if($delete)
            {
                $response = array(
                    "status" => 1,
                    "message" => "Deleted Successfully"
                );
            }
            else
            {

                $response = array(
                    "status" => 0,
                    "message" => "Error"
                );
            }

            return $response;
        }


        public function edit_variation_values(Request $request)
        {
            $vid = $request->header('variationvalueid');
            $data = $request->json()->all();
           
            $delete = $this->_master->variation_value_delete($vid,$data);
            if($delete)
            {
                $response = array(
                    "status" => 1,
                    "message" => "Updated Successfully"
                );
            }
            else
            {

                $response = array(
                    "status" => 0,
                    "message" => "Error"
                );
            }

            return $response;
        }


        public function add_document(Request $request)
        {
           $data = $request->json()->all();
           $s3path = $request->file('file')->store('brandFiles', 's3');
           return array(
               "file_link" => $fullpath = "https://beumontfiles.s3.ap-south-1.amazonaws.com/".$s3path
           );
        }


        public function add_variation_values_map(Request $request)
        {

            $variableId = $request->header('variationid');
            //$variablevalueId = $request->header('variationValueId');
            $data = $request->json()->all();
            $data_array["mapValue"] = $data["name"];
            $data_array["variationid"] = $variableId;
            $data_array["variationValueId"] = 0;
            $data_array["IsDeleted"] = 0;
            $dump = $this->_master->insert_variation_value_map($data_array);
            if($dump)
            {
                $list = $this->_master->variation_list_values_map_added($dump);
                $response = array(
                    "status" => 1,
                    "message" => "Variation Value Map Added Successfully",
                    "Added" => $list
                );
            }
            else
            {

                $response = array(
                    "status" => 0,
                    "message" => "Try Again Later"
                );
            }

            return $response;
        }

        public function list_variation_values_map(Request $request)
        {
            $variableId = $request->header('variationid');
           // $variablevalueId = $request->header('variationValueId');
            $data = $request->json()->all();
            $data["variationid"] = $variableId;
            //$data["variationValueId"] = $variablevalueId;

            $list = $this->_master->variation_list_values_map($variableId);
            if($list)
            {
                $response = array(
                    "status" => 1,
                    "data" => $list
                );
            }
            else
            {

                $response = array(
                    "status" => 0,
                    "message" => "Try Again Later"
                );
            }

            return $response;
        }



        public function delete_variation_values_map(Request $request)
        {
            $vid = $request->header('VariationValueMapId');
    
            $data_array["isDeleted"] = "1";
            $delete = $this->_master->variation_value_delete_map($vid,$data_array);
            if($delete)
            {
                $response = array(
                    "status" => 1,
                    "message" => "Deleted Successfully"
                );
            }
            else
            {

                $response = array(
                    "status" => 0,
                    "message" => "Error"
                );
            }

            return $response;
        }

        public function edit_variation_values_map(Request $request)
        {
            $vid = $request->header('VariationValueMapId');
            $data = $request->json()->all();
            $delete = $this->_master->variation_value_delete_map($vid,$data);
            if($delete)
            {
                $response = array(
                    "status" => 1,
                    "message" => "Updated Successfully"
                );
            }
            else
            {

                $response = array(
                    "status" => 0,
                    "message" => "Error"
                );
            }

            return $response;
        }




        public function add_coupon(Request $request)
        {
            
            
             $data = $request->json()->all();
             
             $check_coupon = $this->_master->check_coupon(1,$data["coupon"],0);
             if($check_coupon == 0)
             {
             $data["isDeleted"] = 1;
             $data["created_date"] = now();
             $dump = $this->_master->insert_coupon($data);
             
             if($dump)
             {

                $list = $this->_master->coupon_list();
                $response = array(
                    "status" => 1,
                    "message" => "Coupon Added Successfully",
                    "list" => $list
                );
             }
             else
             {
    
                $response = array(
                    "status" => 0,
                    "message" => "Try Again Later"
                );
             }
            }
            else{
    
                $response = array(
                    "status" => 0,
                    "message" => "Coupon is already exists"
                );
            }
    
             return $response;
        }



        public function list_coupon(Request $request)
        {
         $list = $this->_master->coupon_list();
         if($list)
         {
            $response = array(
                "status" => 1,
                "data" => $list
            );
         }
         else
         {

            $response = array(
                "status" => 0,
                "message" => "Try Again Later"
            );
         }

         return $response;
    }
 
    public function deactivate_coupon(Request $request)
    {
         $brand = $request->header('couponId');

         $data_array["isDeleted"] = "0";
         $delete = $this->_master->coupon_delete($brand,$data_array);
         if($delete)
         {
            $list = $this->_master->coupon_list();
            $response = array(
                "status" => 1,
                "message" => "Deactivated Successfully",
                "list" => $list
            );
         }
         else
         {

            $response = array(
                "status" => 0,
                "message" => "Error"
            );
         }

         return $response;
    }

    public function activate_coupon(Request $request)
    {
         $brand = $request->header('couponId');

         $data_array["isDeleted"] = "1";
         $delete = $this->_master->coupon_delete($brand,$data_array);
         if($delete)
         {
            $list = $this->_master->coupon_list();
            $response = array(
                "status" => 1,
                "message" => "Deactivated Successfully",
                "list" => $list
            );
         }
         else
         {

            $response = array(
                "status" => 0,
                "message" => "Error"
            );
         }

         return $response;
    }


    public function edit_coupon(Request $request)
    {
         $couponid = $request->header('couponId');
         $data = $request->json()->all();
         
         $check_coupon = $this->_master->check_coupon(2,$data["coupon"],$couponid);
         if($check_coupon == 0)
         {
                $update = $this->_master->coupon_delete($couponid,$data);
                if($update)
                {
                    $list = $this->_master->coupon_list();
                    $response = array(
                        "status" => 1,
                        "message" => "Updated Successfully",
                        "list" => $list
                    );
                }
                else
                {

                    $response = array(
                        "status" => 0,
                        "message" => "Error"
                    );
                }
            }
            else
            {
                
                    $response = array(
                        "status" => 0,
                        "message" => "Coupon is Already exists"
                    );
                
            }

         return $response;
    }



    public function add_home_sliders(Request $request)
    {
         
   
         $data = $request->json()->all();
         $s3path = $request->file('file')->store('homesliders', 's3');
         $type = $request->input('type');
         $typeid = $request->input('typeid');
         $home_text = $request->input('text');
         $data["imageurl"] = "https://beumontfiles.s3.ap-south-1.amazonaws.com/".$s3path;
         $data["type"] = $type;
         $data["typeid"] = $typeid;
         $data["home_text"] = $home_text;
         $dump = $this->_master->insert_home_sliders($data);
         
         if($dump)
         {
            $list = $this->_master->list_home_sliders();
            $response = array(
                "status" => 1,
                "message" => "Added Successfully",
                "list" => $list
            );
         }
         else
         {

            $response = array(
                "status" => 0,
                "message" => "Try Again Later"
            );
         }

         return $response;
    }


    public function list_home_sliders(Request $request)
    {
         $data = $request->json()->all();
         $list = $this->_master->list_home_sliders($data);
         if($list)
         {
            $response = array(
                "status" => 1,
                "data" => $list
            );
         }
         else
         {

            $response = array(
                "status" => 0,
                "message" => "Try Again Later"
            );
         }

         return $response;
    }






    public function delete_home_sliders(Request $request)
    {
         $brand = $request->header('picid');

         $data_array["isDeleted"] = "1";
         $delete = $this->_master->delete_home_sliders($brand,$data_array);
         if($delete)
         {
            $list = $this->_master->list_home_sliders();
            $response = array(
                "status" => 1,
                "message" => "Deleted Successfully",
                "list" => $list
            );
         }
         else
         {

            $response = array(
                "status" => 0,
                "message" => "Error"
            );
         }

         return $response;
    }
    



    public function add_cat3(Request $request)
    {
         $error = new MessageBag();
         $validator = $this->validator($request->json()->all());
         $cat1 = $request->header('cat1');
         $cat2 = $request->header('cat2');
         if($validator->fails())
         {
             return $validator->errors();
         }
         $data = $request->json()->all();
         $data["cat1id"] = $cat1;
         $data["cat2id"] = $cat2;
         $data["isDeleted"] = 0;
         $dump = $this->_master->insert_cat3($data);
         if($dump)
         {
            $response = array(
                "status" => 1,
                "message" => "Category Level 3 Added Successfully"
            );
         }
         else
         {

            $response = array(
                "status" => 0,
                "message" => "Try Again Later"
            );
         }

         return $response;
    }




    public function list_cat3(Request $request)
    {
         $list = $this->_master->cat3_list();
         if($list)
         {
            $response = array(
                "status" => 1,
                "data" => $list
            );
         }
         else
         {

            $response = array(
                "status" => 0,
                "message" => "Try Again Later"
            );
         }

         return $response;
    }


    public function delete_cat3(Request $request)
    {
         $cat3 = $request->header('cat3');

         $data_array["isDeleted"] = "1";
         $delete = $this->_master->cat3_delete($cat3,$data_array);
         if($delete)
         {
            $list = $this->_master->cat3_list();
            $response = array(
                "status" => 1,
                "message" => "Deleted Successfully",
                "list" => $list
            );
         }
         else
         {

            $response = array(
                "status" => 0,
                "message" => "Error"
            );
         }

         return $response;
    }


    public function edit_cat3(Request $request)
    {
         $cat3 = $request->header('cat3');
         $data = $request->json()->all();
         
         $delete = $this->_master->cat3_delete($cat3,$data);
         if($delete)
         {
            $response = array(
                "status" => 1,
                "message" => "Updated Successfully"
            );
         }
         else
         {

            $response = array(
                "status" => 0,
                "message" => "Error"
            );
         }

         return $response;
    }




    public function cat_tree(Request $request)
    {
         
        
         $cat1 = $request->header('cat1');
         $list = $this->_master->cat2_list_by_cat1($cat1);
         $i = 0;
         foreach($list as $single_cat2)
         {
            $cat2id = $single_cat2->id;
            $cat2list = $this->_master->cat3_list_by_cat2($cat2id);
            $list[$i]->cat3 = $cat2list;
            $i++;
         }
         
         
         if($list)
         {
            $response = array(
                "status" => 1,
                "data" => $list
            );
         }
         else
         {

            $response = array(
                "status" => 0,
                "message" => "Try Again Later"
            );
         }

         return $response;
    }



    public function shop_look_image(Request $request)
    {
        $data = $request->json()->all();
        $s3path = $request->file('file')->store('shopTheLook', 's3');
        $fullpath = "https://beumontfiles.s3.ap-south-1.amazonaws.com/".$s3path;
        $data["image"] = $fullpath;
        $update = $this->_master->update_stl($data);
        if($update)
        {
            $response = array(
                "status" => 1,
                "message" => "Updated Successfully"
            );
        }
        else
        {
            $response = array(
                "status" => 0,
                "message" => "Try Again Later"
            );
        }
    }


    public function add_shop_look_category(Request $request)
    {
        $data = $request->json()->all();
        $title = $request->input("category");
        $s3path = $request->file('image')->store('shopTheLook', 's3');
        $fullpath = "https://beumontfiles.s3.ap-south-1.amazonaws.com/".$s3path;
        $data["image"] = $fullpath;
        $data["title"] = $title;
        $data["isDeleted"] = 0;
        $update = $this->_master->add_stl($data);
        if($update)
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
                "message" => "Try Again Later"
            );
        }
        return $response;
    }


    public function list_shop_look_category(Request $request)
    {
        $data = $request->json()->all();

        $list = $this->_master->list_stl($data);
        if($list)
        {
            $response = array(
                "status" => 1,
                "list" => $list
            );
        }
        else
        {
            $response = array(
                "status" => 0,
                "message" => "Try Again Later"
            );
        }
        return $response;
    }

    public function delete_shop_look_category(Request $request)
    {
        $data = $request->json()->all();
        $id = $request->header("id");
        $data["isDeleted"] = 1;
        $delete = $this->_master->stl_cat_update($id,$data);
        if($delete)
        {
            $response = array(
                "status" => 1,
                "message" => "Deleted Successfully"
            );
        }
        else
        {
            $response = array(
                "status" => 0,
                "message" => "Try Again Later"
            );
        }
        return $response;
    }



    public function edit_shop_look_category(Request $request)
    {
        $data = $request->json()->all();
        $title = $request->input("category");
        $image_status = $request->input("change_image");
        if($image_status == 1)
        {
            $s3path = $request->file('image')->store('shopTheLook', 's3');
            $fullpath = "https://beumontfiles.s3.ap-south-1.amazonaws.com/".$s3path;
            $data["image"] = $fullpath;
        }
        $data["title"] = $title;

        $id = $request->header("id");
        $update = $this->_master->stl_cat_update($id,$data);
        if($update)
        {
            $response = array(
                "status" => 1,
                "message" => "Updated Successfully"
            );
        }
        else
        {
            $response = array(
                "status" => 0,
                "message" => "Try Again Later"
            );
        }
        return $response;
    }


    public function add_product_to_category(Request $request)
    {
        $data = $request->json()->all();
        $catid = $request->header("catid");
        $add = $this->_master->add_stl_cat_products($catid,$data);
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
                "message" => "Try Again Later"
            );
        }
        return $response;
    }


    public function list_product_to_category(Request $request)
    {
        $data = $request->json()->all();
        $catid = $request->header("catid");
        $list = $this->_master->list_stl_cat_products($catid);
        if($list)
        {
            $response = array(
                "status" => 1,
                "list" => $list
            );
        }
        else
        {
            $response = array(
                "status" => 0,
                "message" => "Try Again Later"
            );
        }
        return $response;
    }


}


