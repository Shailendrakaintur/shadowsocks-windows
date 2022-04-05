<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\ProductModel;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Hash;
use DB;
use Validator;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
class ProductController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->_product = new ProductModel();
    }

    public function add_product(Request $request)
    {
        $data = $request->json()->all();
        $productrandom = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 6);
        $data_array["product_title"] = $data["product_title"];
        $data_array["category1"] = $data["category1"];
        $data_array["category2"] = $data["category2"];
        $data_array["category3"] = $data["category3"];
        $data_array["manufacture"] = $data["manufacture"];
        $data_array["supplier_name"] = $data["supplier_name"];
        $data_array["supplier_product_id"] = $data["supplier_product_id"];
        $data_array["manufacture_model_number"] = $data["manufacture_model_number"];
        $data_array["wholesale_price"] = $data["wholesale_price"];
        $data_array["recommended_retail_price"] = $data["recommended_retail_price"];
        $data_array["tradePrice"] = $data["tradePrice"];
        $data_array["salePrice"] = $data["salePrice"];
        $data_array["comPrice"] = $data["comPrice"];
        $data_array["color"] = $data["color"];
        $data_array["color_map"] = $data["color_map"];
        $data_array["product_style"] = $data["product_style"];
        $data_array["actual_product_weight"] = $data["actual_product_weight"];
        $data_array["feature_bullet"] = implode('|||',$data["feature_bullet"]);
        $data_array["product_description"] = $data["product_description"];
        $data_array["lead_type"] = $data["lead_type"];
        $data_array["lead_form"] = $data["lead_form"];
        $data_array["lead_to"] = $data["lead_to"];

        
        $pdetails = "";
        foreach($data["product_details"] as $singledetails)
        {
            $pdetails = $pdetails.$singledetails["name"]."*".$singledetails["value"]."|||";
        }
        

        $data_array["product_details"] = $pdetails;


        $data_array["search_keywords"] = $data["search_keywords"];
        $data_array["variation_available"] = $data["variation_available"];
        $data_array["dimension_available"] = $data["dimension_available"];
        $data_array["product_max_width"] = $data["product_max_width"][0]["value"] . "||" . $data["product_max_width"][0]["Unit"];
        $data_array["product_max_depth"] = $data["product_max_depth"][0]["value"] . "||" . $data["product_max_depth"][0]["Unit"];
        $data_array["product_max_height"] = $data["product_max_height"][0]["value"] . "||" . $data["product_max_height"][0]["Unit"];
        $data_array["size1_option"] = $data["size1_option"][0]["value"] . "||" . $data["size1_option"][0]["Unit"];
        $data_array["size2_option"] = $data["size2_option"][0]["value"] . "||" . $data["size2_option"][0]["Unit"];
        $data_array["productRandom"] = $productrandom;
        $data_array["isDeleted"] = 0;



        $productid = $this->_product->insert_product($data_array);
        //dd($productid);
        if ($productid) {
            if ($data["variation_available"] == "yes") {
                $vdata = $data["variation_table_data"];

                foreach ($vdata as $single_vdata) {

                    for ($i = 0; $i < count($single_vdata["variationId"]); $i++) {
                        $vdata1["variationId"] = $single_vdata["variationId"][$i];
                        $vdata1["variationValueId"] = $single_vdata["variationValueId"][$i];
                        $vdata1["variationMapId"] = $single_vdata["variationMapId"][$i];
                        $vdata1["productId"] = $productid;
                        $insert_product_variations = $this->_product->insert_product_variations($vdata1);
                    }

                    $v = implode("*", $single_vdata["variationId"]);
                    $vl = implode("*", $single_vdata["variationValueId"]);
                    $mp = implode("*", $single_vdata["variationMapId"]);
                    $variation_data = $v . "||" . $vl . "||" . $mp;

                    $vdataarray["variation_data"] = $variation_data;
                    $vdataarray["productId"] = $single_vdata["ProductId"];
                    $vdataarray["product_id"] = $productid;
                    $vdataarray["seller_sku"] = $single_vdata["SellerSKU"];
                    $vdataarray["wholesale_price"] = $single_vdata["wholesalePrice"];
                    $vdataarray["full_retail_price"] = $single_vdata["Retail_price"];
                    $vdataarray["product_weight"] = $single_vdata["productWeight"];
                    $insert_product_variations_data = $this->_product->insert_product_variations_data($vdataarray);


                    //dd($variation_data);
                }
            }

            if ($data["dimension_available"] == "yes") {
                $ddata = $data["dimension_data"];

                foreach ($ddata as $single_ddata) {
                    // dd($single_ddata);
                    $ddata1["productid"] = $productid;
                    $ddata1["variationValueId"] = $single_ddata["variationValueId"];
                    $ddata1["width"] = $single_ddata["width"];
                    $ddata1["depth"] = $single_ddata["depth"];
                    $ddata1["height"] = $single_ddata["height"];
                    $ddata1["size1"] = $single_ddata["optional_size"][0]["size1"];
                    $ddata1["size1Value"] = $single_ddata["optional_size"][0]["size1Value"];
                    $ddata1["size2"] = $single_ddata["optional_size"][0]["size2"];
                    $ddata1["size2Value"] = $single_ddata["optional_size"][0]["size2Value"];

                    $dddd = $this->_product->insert_product_dimension($ddata1);
                }
            }
            $response = array(
                "status" => 1,
                "productId" => $productid,
                "product_title" =>  $data["product_title"],
                "message" => "Product Added Successfully"
            );
        } else {

            $response = array(
                "status" => 0,
                "message" => "Try Again Later"
            );
        }


        return $response;
    }



    public function list_product(Request $request)
    {

        $data = $request->json()->all();
        $product_details = $this->_product->product_list($data);
        $i = 0;
        foreach ($product_details as $data) {
            $id = $data->id;

            $variation_data = $this->_product->product_data($id);
            $m = 0;
            foreach ($variation_data as $value) {


                $split = explode("||", $value->variation_data);

                $variation_data[$m]->variationId = explode("*", $split[0]);
                $variation_data[$m]->variationValueId = explode("*", $split[1]);
                $variation_data[$m]->variationMapId = explode("*", $split[2]);
                unset($variation_data[$m]->variation_data);
                $m++;
            }



            $dimension = $this->_product->product_dimension_data($id);




            $product_details[$i]->variation_table_data = $variation_data;
            $product_details[$i]->dimension_data = $dimension;

            $i++;
        }


        return $product_details;
    }

    public function edit_product(Request $request)
    {
        $data = $request->json()->all();
        $data["product_max_width"] = $data["product_max_width"][0]["value"] . "||" . $data["product_max_width"][0]["Unit"];
        $data["product_max_depth"] = $data["product_max_depth"][0]["value"] . "||" . $data["product_max_depth"][0]["Unit"];
        $data["product_max_height"] = $data["product_max_height"][0]["value"] . "||" . $data["product_max_height"][0]["Unit"];
        $data["size1_option"] = $data["size1_option"][0]["value"] . "||" . $data["size1_option"][0]["Unit"];
        $data["size2_option"] = $data["size2_option"][0]["value"] . "||" . $data["size2_option"][0]["Unit"];


        $productid = $request->header('productid');
        $product_update = $this->_product->product_update($productid, $data);
        if ($product_update) {
            $response = array(
                "status" => 1,
                "message" => "Product Updated Successfully"
            );
        } else {
            $response = array(
                "status" => 0,
                "message" => "Error try again later"
            );
        }

        return $response;
    }

    public function delete_product(Request $request)
    {

        $data_array["isDeleted"] = 1;
        $productid = $request->header('productid');
        $product_update = $this->_product->product_update($productid, $data_array);
        if ($product_update) {
            $product_list = $this->_product->product_list();
            $response = array(
                "status" => 1,
                "message" => "Product Deleted Successfully",
                "list" => $product_list
            );
        } else {
            $response = array(
                "status" => 0,
                "message" => "Error try again later"
            );
        }

        return $response;
    }

    public function add_product_images(Request $request)
    {
        $data = $request->json()->all();
        $productid = $request->header('productId');
  
        $variationvalueId = $request->header('variationvalueId');
        $main = $request->header('main');
        $s3path = $request->file('file')->store('productFiles', 's3');
        $fullpath = "https://beumontfiles.s3.ap-south-1.amazonaws.com/".$s3path;
        $data_array["product_id"] = $productid;
        $data_array["varitionValueId"] = $variationvalueId;
        $data_array["image"] = $fullpath;
        $data_array["main"] = $main;

        $product_update_image = $this->_product->add_product_image($data_array);
        if($product_update_image)
        {
    
            $response = array(
                "status" => 1,
                "message" => "Uploaded Successfully",
                "imageid" => $product_update_image,
                "images/files" => $fullpath
            );
        }
        else
        {
            $response = array(
                "status" => 0,
                "message" => "Error try again later"
            );
        }

        return $response;
    }


    public function list_product_images(Request $request)
    {
        $productid = $request->header('productId');
        $files = $this->_product->product_list_images($productid);
        $response = array(
            "status" => 1,
            "images/files" => $files
        );
        return $response;
    }


    public function delete_product_images(Request $request)
    {
        $data = $request->json()->all();
       // $s3file = str_replace("https://beumontfiles.s3.ap-south-1.amazonaws.com/","",$data["fileurl"]);
        //dd($file);
        $imageid = $request->header('imageid');
        $productid = $request->header('productId');
        $files = $this->_product->delete_pimage($imageid);
        if($files)
        {
            $list_files = $this->_product->product_list_images($productid);
         //   $s3path = Storage::disk('s3')->delete($s3file);
            $response = array(
                "status" => 1,
                "message" => "Deleted Successfully",
                "images/files" => $list_files
            );
            
        }
        else
        {
            $response = array(
                "status" => 0,
                "message" => "Failed To Delete file",
                "images/files" => $files
            );

        }
        return $response;
    }


    public function add_products_to_brand(Request $request)
    {
        $data = $request->json()->all();
        $brandid = $request->header('brandId');
        $data["brandId"] = $brandid;
        $add = $this->_product->add_brand_products($data);
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


    public function add_featured_products(Request $request)
    {
        $data = $request->json()->all();
        $add = $this->_product->add_fproducts($data);
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

    public function list_featured_products(Request $request)
    {
        $data = $request->json()->all();
        $list = $this->_product->list_fproducts($data);
    
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
                "message" => "Failed"
            );  
        }

        return $response;

    }

    


    public function list_product_by_cat(Request $request)
    {

        $data = $request->json()->all();

        $cat1 = $request->header('cat1');
        $cat2 = $request->header('cat2');
        $cat3 = $request->header('cat3');
        $data["cat1"] = $cat1;
        $data["cat2"] = $cat2;
        $data["cat3"] = $cat3;
        $product_details = $this->_product->product_list($data);
        $i = 0;
        foreach ($product_details as $data) {
            $id = $data->id;

            $variation_data = $this->_product->product_data($id);
            
            $m = 0;
            foreach ($variation_data as $value) {


                $split = explode("||", $value->variation_data);


                
                foreach(explode("*", $split[0]) as $single_variation)
                {
           
                    $variation_title = DB::table('variation')->select("variationTitle")->where("id",$single_variation)->get(); 
                    if(isset($variation_title[0]->variationTitle))
                    {
                        $variation_array[] = array(
                            "variation_id" => $single_variation,
                            "variation_title" => $variation_title[0]->variationTitle
                        );
                    }
                    else
                    {
                        $variation_array[] = array(
                            "variation_id" => $single_variation,
                            "variation_title" => ""
                        );
                    }
                }
   


                foreach(explode("*", $split[1]) as $single_variation_value)
                {
           
                    $variation_value_title = DB::table('variation_value')->select("variation_value")->where("id",explode("*", $single_variation))->get(); 
                    if(isset($variation_value_title[0]->variation_value))
                    {
                        $variation_value_array[] = array(
                            "variation_value_id" => $single_variation_value,
                            "variation_value_title" => $variation_value_title[0]->variation_value
                        );
                    }
                    else
                    {
                        $variation_value_array[] = array(
                            "variation_id" => $single_variation,
                            "variation_title" => ""
                        );
                    }
                }




                foreach(explode("*", $split[2]) as $single_variation_map)
                {
           
                    $variation_map_title = DB::table('variation_value_map')->select("mapValue")->where("id",explode("*", $single_variation_map))->get(); 
                    if(isset($variation_map_title[0]->mapValue))
                    {
                        $variation_map_array[] = array(
                            "variation_map_id" => $single_variation_map,
                            "variation_map_title" => $variation_map_title[0]->mapValue
                        );
                    }
                    else
                    {
                        $variation_map_array[] = array(
                            "variation_map_id" => $single_variation_map,
                            "variation_map_title" => ""
                        );
                    }
                }


               
                $variation_data[$m]->variations = $variation_array;
                $variation_data[$m]->variationValue = $variation_value_array;
                $variation_data[$m]->variationMap = $variation_map_array;
                
                unset($variation_data[$m]->variation_data);
                $m++;
            }


            $dimension = $this->_product->product_dimension_data($id);



            $product_details[$i]->variation_table_data = $variation_data;
            $product_details[$i]->dimension_data = $dimension;

            $i++;
        }


        return $product_details;
    }

    public function product_byid(Request $request)
    {

        $data = $request->json()->all();
        $product_details = $this->_product->product_byid_list($data);
        $i = 0;
        foreach ($product_details as $data) {
            $id = $data->id;

            $variation_data = $this->_product->product_data($id);
            $m = 0;
            foreach ($variation_data as $value) {


                $split = explode("||", $value->variation_data);

                $variation_data[$m]->variationId = explode("*", $split[0]);
                $variation_data[$m]->variationValueId = explode("*", $split[1]);
                //$variation_data[$m]->variationMapId = explode("*", $split[2]);
                unset($variation_data[$m]->variation_data);
                $m++;
            }



            $dimension = $this->_product->product_dimension_data($id);




            $product_details[$i]->variation_table_data = $variation_data;
            $product_details[$i]->dimension_data = $dimension;

            $i++;
        }


        return $product_details;
    }


    public function single_product(Request $request)
    {

        $data = $request->json()->all();

        $product = $request->header('productid');
        $data["productid"] = $product;
        $product_details = $this->_product->product_list($data);
        $i = 0;
        foreach ($product_details as $data) 
        {



            $id = $data->id;
            $supplier_id = $data->supplier_name;
            $getbrand = DB::table('brands')->select('brand_name')->where('Id',$supplier_id)->get();
          
            $product_details[$i]->brand_id = $data->supplier_name;
            $product_details[$i]->brand_name = $getbrand[0]->brand_name;
            
            $variation_data = $this->_product->product_data($id);
            

            
            $m = 0;
            $for_image = [];
            $final_image_array = [];
            foreach ($variation_data as $value) {


                $split = explode("||", $value->variation_data);


                  
                    
                    for($s=0;$s<=count(explode("*", $split[0]))-1;$s++)
                    {

                      
                        $variation_value_id = explode("*", $split[1])[$s];
                        $variation_id = explode("*", $split[0])[$s];
                        $variation_title = DB::table('variation')->select("variationTitle")->where("id",$variation_id)->get(); 
                        $variation_value_title = DB::table('variation_value')->select("variation_value")->where("id",$variation_value_id)->get(); 
                        
                        if(isset($variation_title[0]->variationTitle))
                        {
                            $vrtitle = $variation_title[0]->variationTitle;
                        }
                        else
                        {
                            $vrtitle = "";
                        }
                        if(isset($variation_value_title[0]->variation_value))
                        {
                            $vltitle = $variation_value_title[0]->variation_value;
                        }
                        else
                        {
                            $vltitle = "";
                        }

                        $image_array = array(
                            "variation_name"=> $vrtitle,
                            "valuename" => $vltitle,
                            "valueid" => $variation_value_id
                        );
                        array_push($final_image_array,$image_array);
                    }
              

             
       

                foreach(explode("*", $split[0]) as $single_variation)
                {
           
                    $variation_title = DB::table('variation')->select("variationTitle")->where("id",$single_variation)->get(); 
                    if(isset($variation_title[0]->variationTitle))
                    {
                        $variation_array[] = array(
                            "variation_id" => $single_variation,
                            "variation_title" => $variation_title[0]->variationTitle
                        );
                    }
                    else
                    {
                        $variation_array[] = array(
                            "variation_id" => $single_variation,
                            "variation_title" => ""
                        );
                    }
                }
   


                foreach(explode("*", $split[1]) as $single_variation_value)
                {
           
                    $variation_value_title = DB::table('variation_value')->select("variation_value")->where("id",explode("*", $single_variation_value))->get(); 
                    if(isset($variation_value_title[0]->variation_value))
                    {
                        $variation_value_array[] = array(
                            "variation_value_id" => $single_variation_value,
                            "variation_value_title" => $variation_value_title[0]->variation_value
                        );
                    }
                    else
                    {
                        $variation_value_array[] = array(
                            "variation_id" => $single_variation,
                            "variation_title" => ""
                        );
                    }
                }




                foreach(explode("*", $split[2]) as $single_variation_map)
                {
           
                    $variation_map_title = DB::table('variation_value_map')->select("mapValue")->where("id",explode("*", $single_variation_map))->get(); 
                    if(isset($variation_map_title[0]->mapValue))
                    {
                        $variation_map_array[] = array(
                            "variation_map_id" => $single_variation_map,
                            "variation_map_title" => $variation_map_title[0]->mapValue
                        );
                    }
                    else
                    {
                        $variation_map_array[] = array(
                            "variation_map_id" => $single_variation_map,
                            "variation_map_title" => ""
                        );
                    }
                }


               
                $variation_data[$m]->variations = $variation_array;
                $variation_data[$m]->variationValue = $variation_value_array;
                $variation_data[$m]->variationMap = $variation_map_array;
                

            
                unset($variation_array);
                unset($variation_value_array);
                unset($variation_map_array);
            

                unset($variation_data[$m]->variation_data);
                $m++;
            }



            $dimension = $this->_product->product_dimension_data($id);
            




            $product_details[$i]->variation_table_data = $variation_data;
            $product_details[$i]->dimension_data = $dimension;

            $product_details[$i]->variation_for_image = $final_image_array;

            $i++;
        }


        return $product_details;
    }




    public function add_shipping(Request $request)
    {
         $error = new MessageBag();
       
         $data = $request->json()->all();
         $data["isDeleted"] = 0;
         $dump = $this->_product->insert_shipping($data);
         if($dump)
         {
            $response = array(
                "status" => 1,
                "message" => "Shipping template  Added Successfully",
                "inserted id" => $dump
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


    public function list_shipping(Request $request)
    {
         $list = $this->_product->shipping_list();
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



    public function delete_shipping(Request $request)
    {
         $id = $request->header('id');

         $data_array["isDeleted"] = "1";
         $delete = $this->_product->shipping_delete($id,$data_array);
         if($delete)
         {
            $list = $this->_product->shipping_list();
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


    public function edit_shipping(Request $request)
    {
         $id = $request->header('id');
         $data = $request->json()->all();
         
         $update = $this->_product->shipping_delete($id,$data);
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
                "message" => "Error"
            );
         }

         return $response;
    }

    public function add_region(Request $request)
    {
         $error = new MessageBag();
        
         $shipping = $request->header('shipping');
        
         $data = $request->json()->all();
         $data["id"] = $shipping;
         $data["isDeleted"] = 0;
         $dump = $this->_product->insert_region($data);
         if($dump)
         {
            $response = array(
                "status" => 1,
                "message" => "Region Added Successfully"
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


    public function list_region(Request $request)
    {
         $list = $this->_product->region_list();
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



    public function delete_region(Request $request)
    {
         $id = $request->header('id');

         $data_array["isDeleted"] = "1";
         $delete = $this->_product->region_delete($id,$data_array);
         if($delete)
         {
            $list = $this->_product->region_list();
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

    public function edit_region(Request $request)
    {
         $id = $request->header('id');
         $data = $request->json()->all();
         
         $update = $this->_product->region_delete($id,$data);
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
                "message" => "Error"
            );
         }

         return $response;
    }

    public function add_product_quickbooks(Request $request)
    {
        $data = $request->json()->all();
        
        $add = $this->_product->add_quickbooks_products($data);
        if($add)
        {
            $response = array(
                "status" => 1,
                "message" => "Added quickbook Successfully"
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


    public function list_users(Request $request)
    {
        $data = $request->json()->all();
         $list = $this->_product->useres_list();
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
    



}
