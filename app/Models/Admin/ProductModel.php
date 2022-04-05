<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class ProductModel extends Model
{
    use HasFactory;

    public function insert_product($data_array)
    {
        $productid = DB::table('product')->insertGetId($data_array);
        return $productid;
    }

    public function insert_variables($productid,$variables)
    {

        $delete = db::table("product_vs_variable")->where("productid",$productid)->delete();
        foreach($variables as $val)
        { 
            
            $data_array1["productid"] = $productid;
            $data_array1["variableId"] = $val["variableid"];
            $data_array1["variableValue"] = $val["variableValue"];
            $data_array1["productRandom"] = "fsdf";
            $dump = DB::table('product_vs_variable')->insertGetId($data_array1);
        }

       
            if($dump)
            {
                return 1;
            }
            else
            {
                return 0;
            }



    }


    public function product_list($data)
    {

       
        if(isset($data["productid"]))
        {
            $pid = $data["productid"];
            $products = DB::table('product')->where("id",$pid)->where("isDeleted",0)->get();
        }
        else
        if(isset($data["cat1"]))
        {
           
            $products = DB::table('product')->where("category1",$data["cat1"])->where("category2",$data["cat2"])->where("category3",$data["cat3"])->where("isDeleted",0)->get();
        }
        else
        if($data["type"] == "all")
        {
            $products = DB::table('product')->where("isDeleted",0)->get(); 
        }
        else
        {
            $products = DB::table('product')->where("isDeleted",0)->get(); 
        }

        
        $i = 0;
        foreach($products as $single)
        {
            $width_split = explode('||',$single->product_max_width);
            $new_product_max_width['value'] = $width_split[0];
            $new_product_max_width['unit'] = $width_split[1];

            $depth_split = explode('||',$single->product_max_depth);
            $new_product_max_depth['value'] = $depth_split[0];
            $new_product_max_depth['unit'] = $depth_split[1];

            $height_split = explode('||',$single->product_max_height);
            $new_product_max_height['value'] = $height_split[0];
            $new_product_max_height['unit'] = $height_split[1];

            
            $size1_split = explode('||',$single->size1_option);
            $new_size1['value'] = $size1_split[0];
            $new_size1['unit'] = $size1_split[1];

            
                        
            $size2_split = explode('||',$single->size2_option);
            $new_size2['value'] = $size2_split[0];
            $new_size2['unit'] = $size2_split[1];


            $new_pd = array();
            $product_details_split = explode('|||',$single->product_details);
            foreach($product_details_split as $pd)
            {
                $pd1 = explode("*",$pd);
                if($pd1[0] != "")
                {
                $npd = array(
                    "name" => $pd1[0],
                    "value" => $pd1[1]
                );
                $new_pd[] = $npd;
            }
            }

            
            
            $products[$i]->product_max_width = $new_product_max_width;
            $products[$i]->product_max_depth = $new_product_max_depth;
            $products[$i]->product_max_height = $new_product_max_height;
            $products[$i]->size1_option = $new_size1;
            $products[$i]->size2_option = $new_size2;
            $products[$i]->feature_bullet = explode('|||',$single->feature_bullet);
            $products[$i]->product_details = $new_pd;
            $i++;
        }
        return $products;
    }

    public function product_byid_list($data)
    {

       
        if(isset($data["productid"]))
        {
            $pid = $data["productid"];
            $products = DB::table('product')->where("id",$pid)->where("isDeleted",0)->get();
        }
        else
        if(isset($data["cat1"]))
        {
           
            $products = DB::table('product')->where("category1",$data["cat1"])->where("category2",$data["cat2"])->where("category3",$data["cat3"])->where("isDeleted",0)->get();
        }
        else
        if($data["type"] == "all")
        {
            $products = DB::table('product')->where("isDeleted",0)->get(); 
        }
        else
        {
            $products = DB::table('product')->where("isDeleted",0)->get(); 
        }

        
        $i = 0;
        foreach($products as $single)
        {
            $width_split = explode('||',$single->product_max_width);
            $new_product_max_width['value'] = $width_split[0];
            $new_product_max_width['unit'] = $width_split[1];

            $depth_split = explode('||',$single->product_max_depth);
            $new_product_max_depth['value'] = $depth_split[0];
            $new_product_max_depth['unit'] = $depth_split[1];

            $height_split = explode('||',$single->product_max_height);
            $new_product_max_height['value'] = $height_split[0];
            $new_product_max_height['unit'] = $height_split[1];

            
            $size1_split = explode('||',$single->size1_option);
            $new_size1['value'] = $size1_split[0];
            $new_size1['unit'] = $size1_split[1];

            
                        
            $size2_split = explode('||',$single->size2_option);
            $new_size2['value'] = $size2_split[0];
            $new_size2['unit'] = $size2_split[1];


            $new_pd = array();
            $product_details_split = explode('|||',$single->product_details);
            foreach($product_details_split as $pd)
            {
                $pd1 = explode("*",$pd);
                if($pd1[0] != "")
                {
                $npd = array(
                    "name" => $pd1[0],
                    "value" => $pd1[1]
                );
                $new_pd[] = $npd;
            }
            }

            
            
            $products[$i]->product_max_width = $new_product_max_width;
            $products[$i]->product_max_depth = $new_product_max_depth;
            $products[$i]->product_max_height = $new_product_max_height;
            $products[$i]->size1_option = $new_size1;
            $products[$i]->size2_option = $new_size2;
            $products[$i]->feature_bullet = explode('|||',$single->feature_bullet);
            $products[$i]->product_details = $new_pd;
            $i++;
        }
        return $products;
    }

 

    public function product_update($id,$data) 
    {
        $product = DB::table('product')->where("id",$id)->update($data);
        return $product;
    }


    public function insert_product_variations($data_array)
    {
        $productvarid = DB::table('product_variations')->insertGetId($data_array);
        return $productvarid;
    }


    

    public function insert_product_variations_data($data_array)
    {
        $productvarid = DB::table('product_variation_data')->insertGetId($data_array);
        return $productvarid;
    }

    public function insert_product_dimension($data_array)
    {
        $productvarid = DB::table('product_dimension')->insertGetId($data_array);
        return $productvarid;
    }

    public function product_data($pid)
    {
        $products = DB::table('product_variation_data')->where("product_id",$pid)->get();

        return $products;
    }

    public function product_dimension_data($pid)
    {
        $products = DB::table('product_dimension')->where("productid",$pid)->get();
        return $products;
    }


    
    public function add_product_image($data_array)
    {
        if($data_array["main"] == 1)
        {
            $productid = DB::table('product_images')->where("main",1)->delete();
        }
        $productid = DB::table('product_images')->insertGetId($data_array);
        return $productid;
    }

    public function product_list_images($pid)
    {
        $products = DB::table('product_images')->where("product_id",$pid)->get();
        return $products;
    }

    public function delete_pimage($pid)
    {
        $products = DB::table('product_images')->where("id",$pid)->delete();
        return $products;
    }

    public function add_brand_products($data)
    {
        $delete = DB::table('brand_products')->where("brandId",$data["brandId"])->delete();
        foreach($data["productId"] as $product)
        {
            $newdata["productId"] = $product;
            $newdata["brandId"] = $data["brandId"];
            $productid = DB::table('brand_products')->insertGetId($newdata);
        }
        
        return $productid;
    }

    public function add_fproducts($data)
    {
        $delete = DB::table('featured_products')->where("cat1",$data["cat1"])->delete();
        foreach($data["products"] as $product)
        {
            $newdata["products"] = $product;
            $newdata["cat1"] = $data["cat1"];
            $productid = DB::table('featured_products')->insertGetId($newdata);
        }
        return $productid;
    }

    public function list_fproducts()
    {
        $cats = DB::table('featured_products')->distinct()->get(['cat1']);
        $i = 0;
      
        foreach($cats as $catid)
        {
           
            $id = $catid->cat1;
            $products = DB::table('featured_products')->select("products")->where("cat1",$id)->pluck("products")->toArray();
            $product_array = array();
            foreach($products as $singlep)
            {
                $products_details = DB::table('product')->select('id','product_title')->where("id",$singlep)->get();
                $pics = DB::table('product_images')->select('image')->where("product_id",$singlep)->get(); 
                $products_details[0]->images = $pics;

            
                $product_array[] = $products_details[0]; 
            }
           
            $catname1 = DB::table('cat1')->select('title')->where("id",$id)->get(); 
          
            $title = $catname1[0]->title;
            $cats[$i]->catname = "asd";
            $cats[$i]->products = $product_array;
            $i++;
        }

       

        return $cats;
    }

    public function insert_shipping($data_array)
    {
        $shipping = DB::table('product_shipping')->insertGetId($data_array);
        return $shipping;
    }

    
    public function shipping_list()
    {
        $shipping = DB::table('product_shipping')->select("id","name","location","shipping_type","delivery_type","zone")->where("isDeleted",0)->get();
        return $shipping;
    }

    public function shipping_delete($id,$data)
    {
        $shipping = DB::table('product_shipping')->where("Id",$id)->update($data);
        return $shipping;
    }



    public function insert_region($data_array)
    {
        $region = DB::table('product_region')->insertGetId($data_array);
        return $region;
    }

    
    public function region_list()

    {
        $region = DB::table('product_region')->select("id","region","delivarycost","minimum_weight","max_weight","fee","region_type")->where("isDeleted",0)->get();
        return $region;
    }

    public function region_delete($id,$data) 
    {
        $region = DB::table('product_region')->where("Id",$id)->update($data);
        return $region;

    }

    public function add_quickbooks_products($data_array)
    {
        $quickbooks = DB::table('product_quickbooks')->insertGetId($data_array);
        return $quickbooks;
    }

    public function useres_list()
    {
         $list = DB::table('users')->select("uid","email","password","title","first_name","last_name","country","verification_code","created_at","updated_at","verified")->where("isDeleted",0)->get();
        return $lists;
    
    }
 
// <<<<<<< HEAD

// =======
// >>>>>>> >>>>>>>>>>>>>>>>>>>>
 }
