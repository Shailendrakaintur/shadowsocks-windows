<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class MasterModel extends Model
{
    use HasFactory;

    public function insert_brands($data_array)
    {
        $brands = DB::table('brands')->insertGetId($data_array);
        return $brands;
    }

    
    public function brand_list($data)
    {
        if(!isset($data["type"]))
        {
           
            $brands = DB::table('brands')->select('Id', 'bussiness_company_name', 'bussiness_website_url', 'bussiness_addressline1', 'bussiness_addressline2', 'bussiness_city', 'opup_country_province','bussiness_country', 'bussiness_pincode', 'bussiness_details_company_rnumber', 'bussiness_details_which_market_u_sell', 'bussiness_details_select_all_countries', 'bussiness_details_products_in_catalog', 'bussiness_details_dropship', 'bussiness_details_accept_returns', 'bussiness_details_categories_offfer', 'bussiness_details_online_retailers', 'bussiness_details_stock_product', 'bussiness_details_slt_from', 'bussiness_details_slt_to', 'bussiness_details_slt_duration', 'brand_name', 'brand_show_name', 'brand_description', 'brand_logo', 'brand_document', 'brand_catalogue', 'brand_backgroundimage1', 'brand_backgroundimage2', 'brand_backgroundimage3', 'brand_pricelist', 'brand_video', 'contact_full_name', 'contact_email', 'contact_phone_country_code', 'contact_phone_number', 'contact_your_role', 'contact_list_brands_represent', 'opup_addressline1', 'opup_addressline2','opup_addressline3', 'opup_city', 'opup_country', 'opup_pincode', 'pc_name', 'pc_surname', 'pc_email', 'pc_telephone', 'bank_holder_name', 'bank_location', 'bank_account_number', 'bank_sort_code', 'bank_iban', 'bank_swift_code', 'fc_to_residential', 'fc_to_commercial', 'pickup_and_delivery', 'isDeleted')->where("isDeleted",0)->get();
        }
        else
        if($data["type"] == "all")
        {
            $brands = DB::table('brands')->select('Id', 'bussiness_company_name', 'bussiness_website_url', 'bussiness_addressline1', 'bussiness_addressline2', 'bussiness_city', 'opup_country_province','bussiness_country', 'bussiness_pincode', 'bussiness_details_company_rnumber', 'bussiness_details_which_market_u_sell', 'bussiness_details_select_all_countries', 'bussiness_details_products_in_catalog', 'bussiness_details_dropship', 'bussiness_details_accept_returns', 'bussiness_details_categories_offfer', 'bussiness_details_online_retailers', 'bussiness_details_stock_product', 'bussiness_details_slt_from', 'bussiness_details_slt_to', 'bussiness_details_slt_duration', 'brand_name', 'brand_show_name', 'brand_description', 'brand_logo', 'brand_document', 'brand_catalogue', 'brand_backgroundimage1', 'brand_backgroundimage2', 'brand_backgroundimage3', 'brand_pricelist', 'brand_video', 'contact_full_name', 'contact_email', 'contact_phone_country_code', 'contact_phone_number', 'contact_your_role', 'contact_list_brands_represent', 'opup_addressline1', 'opup_addressline2','opup_addressline3', 'opup_city', 'opup_country', 'opup_pincode', 'pc_name', 'pc_surname', 'pc_email', 'pc_telephone', 'bank_holder_name', 'bank_location', 'bank_account_number', 'bank_sort_code', 'bank_iban', 'bank_swift_code', 'fc_to_residential', 'fc_to_commercial', 'pickup_and_delivery', 'isDeleted')->where("isDeleted",0)->get();
            
        }
        else
        {
            $bid = $data["brandId"];
            $brands = DB::table('brands')->select('Id', 'bussiness_company_name', 'bussiness_website_url', 'bussiness_addressline1', 'bussiness_addressline2', 'bussiness_city', 'opup_country_province','bussiness_country', 'bussiness_pincode', 'bussiness_details_company_rnumber', 'bussiness_details_which_market_u_sell', 'bussiness_details_select_all_countries', 'bussiness_details_products_in_catalog', 'bussiness_details_dropship', 'bussiness_details_accept_returns', 'bussiness_details_categories_offfer', 'bussiness_details_online_retailers', 'bussiness_details_stock_product', 'bussiness_details_slt_from', 'bussiness_details_slt_to', 'bussiness_details_slt_duration', 'brand_name', 'brand_show_name', 'brand_description', 'brand_logo', 'brand_document', 'brand_catalogue', 'brand_backgroundimage1', 'brand_backgroundimage2', 'brand_backgroundimage3', 'brand_pricelist', 'brand_video', 'contact_full_name', 'contact_email', 'contact_phone_country_code', 'contact_phone_number', 'contact_your_role', 'contact_list_brands_represent', 'opup_addressline1', 'opup_addressline2','opup_addressline3', 'opup_city', 'opup_country', 'opup_pincode', 'pc_name', 'pc_surname', 'pc_email', 'pc_telephone', 'bank_holder_name', 'bank_location', 'bank_account_number', 'bank_sort_code', 'bank_iban', 'bank_swift_code', 'fc_to_residential', 'fc_to_commercial', 'pickup_and_delivery', 'isDeleted')->where("Id",$bid)->where("isDeleted",0)->get();
        }

  
        $i = 0;
        foreach($brands as $value)
        {
            $id = $value->Id;
           
           
            $products = DB::table('brand_products')->select("productId")->where("brandId",$id)->pluck('productId')->toArray();
            $brands[$i]->brand_products = $products;
            $brands[$i]->bussiness_details_select_all_countries = explode('||',$value->bussiness_details_select_all_countries);
            $i++;
        }
        
      
        return $brands;
    }

    public function brand_delete($id,$data)
    {
        $brands = DB::table('brands')->where("Id",$id)->update($data);
        return $brands;
    }



    
    public function insert_cat1($data_array)
    {
        $cat1 = DB::table('cat1')->insertGetId($data_array);
        return $cat1;
    }

    
    public function cat1_list()
    {
        $cat1 = DB::table('cat1')->select("id","title")->where("isDeleted",0)->get();
        return $cat1;
    }

    public function cat1_delete($id,$data)
    {
        $cat1 = DB::table('cat1')->where("Id",$id)->update($data);
        return $cat1;
    }


    public function insert_cat2($data_array)
    {
        $cat2 = DB::table('cat2')->insertGetId($data_array);
        return $cat2;
    }

    
    public function cat2_list()
    {
        $cat2 = DB::table('cat2')->select("id","title","cat1id","image_url")->where("isDeleted",0)->get();
        return $cat2;
    }

    public function cat2_delete($id,$data) 
    {
        $cat2 = DB::table('cat2')->where("Id",$id)->update($data);
        return $cat2;
    }






    public function insert_variation($data_array)
    {
       
        $count = DB::table('variation')->select("variationTitle")->where("variationTitle",$data_array["variationTitle"])->get();
        if(count($count) > 0)
        {
            return 999;
        }
        else
        {
            $var = DB::table('variation')->insertGetId($data_array);
            return $var;
        }
        
     
    }

    
    public function variation_list()
    {
        $var = DB::table('variation')->select("id","variationTitle")->where("isDeleted",0)->get();
        return $var;
    }

    
    public function variation_list_added($id)
    {
        $var = DB::table('variation')->select("id","variationTitle")->where("id",$id)->get();
        return $var;
    }


    public function variation_delete($id,$data) 
    {
        $var = DB::table('variation')->where("id",$id)->update($data);
        return $var;
    }


    public function insert_variation_value($data_array)
    {
        $var = DB::table('variation_value')->insertGetId($data_array);
        return $var;
    }

    public function variation_list_values($vid)
    {
        $var = DB::table('variation_value')->select("id","variationid","variation_value")->where("variationid",$vid)->where("isDeleted",0)->get();
        return $var;
    }

    public function variation_list_values_added($id)
    {
        $var = DB::table('variation_value')->select("id","variationid","variation_value")->where("id",$id)->get();
        return $var;
    }

    public function variation_value_delete($id,$data) 
    {
        $var = DB::table('variation_value')->where("id",$id)->update($data);
        return $var;
    }


    public function insert_variation_value_map($data_array)
    {
        $var = DB::table('variation_value_map')->insertGetId($data_array);
        return $var;
    }

    public function variation_list_values_map($vid)
    {
        $var = DB::table('variation_value_map')->select("id as VariationValueMapId","variationid","variationValueId","mapValue")->where("variationid",$vid)->where("isDeleted",0)->get();
        return $var;
    }

    public function variation_list_values_map_added($id)
    {
        $var = DB::table('variation_value_map')->select("id as VariationValueMapId","variationid","variationValueId","mapValue")->where("id",$id)->get();
        return $var;
    }

    public function variation_value_delete_map($id,$data) 
    {
        $var = DB::table('variation_value_map')->where("id",$id)->update($data);
        return $var;
    }


    public function insert_coupon($data_array)
    {
        $brands = DB::table('coupons')->insertGetId($data_array);
        return $brands;
    }

    public function check_coupon($type,$coupon,$couponid)
    {
        if($type == 1 && $couponid == 0)
        {
            $cc = DB::table('coupons')->where("coupon",$coupon)->get();
        }
        else
        {
            $cc = DB::table('coupons')->where("coupon",$coupon)->where('id',"!=",$couponid)->get();
            
        }
       
        return $cc->count();
    }

    
    public function coupon_list()
    {
        $brands = DB::table('coupons')->select('id','coupon','created_date','from_date','to_date','price_or_percentage','max_cart_price','isDeleted as Active')->get();
        return $brands;
    }

    public function coupon_delete($id,$data)
    {
        $brands = DB::table('coupons')->where("Id",$id)->update($data);
        return $brands;
    }


      
    public function insert_home_sliders($data_array)
    {
        $pic = DB::table('home_sliders')->insertGetId($data_array);
        return $pic;
    }

    
    public function list_home_sliders()
    {
        $pic = DB::table('home_sliders')->get();
        $i = 0;
        foreach($pic as $value)
        {
            if($value->type == 1)
            {
                $catname1 = DB::table('cat1')->select('title')->where("id",$value->typeid)->get(); 
            
                $title = $catname1[0]->title;
                $pic[$i]->catname = $title;
                $pic[$i]->productname = "";
            }
            else
            {
                $productname = DB::table('product')->select('product_title')->where("id",$value->typeid)->get(); 
            
                $title = $productname[0]->product_title;
                $pic[$i]->catname = "";
                $pic[$i]->productname = $title;
            }
        $i++;
        }
        return $pic;
    }

    public function delete_home_sliders($id)
    {
        $pic = DB::table('home_sliders')->where("id",$id)->delete();
        return $pic;
    }


    public function insert_cat3($data_array)
    {
        $cat3 = DB::table('cat3')->insertGetId($data_array);
        return $cat3;
    }


    public function cat3_list()
    {
        $cat3 = DB::table('cat3')->select("id","title","cat1id","cat2id")->where("isDeleted",0)->get();
        return $cat3;
    }

    public function cat3_delete($id,$data) 
    {
        $cat3 = DB::table('cat3')->where("Id",$id)->update($data);
        return $cat3;
    }

        
    public function cat2_list_by_cat1($cat1)
    {
        $cat2 = DB::table('cat2')->select("id","title","cat1id","image_url")->where("isDeleted",0)->where("cat1id",$cat1)->get();
        return $cat2;
    }

    public function cat3_list_by_cat2($cat2)
    {
        $cat2 = DB::table('cat3')->select("id","title","cat1id","cat2id")->where("isDeleted",0)->where("cat2id",$cat2)->get();
        return $cat2;
    }



    public function update_stl($data) 
    {
        $cat2 = DB::table('shop_look_pic')->where("id",1)->update($data);
        return $cat2;
    }

    public function add_stl($data_array)
    {
        $stl = DB::table('stl_category')->insertGetId($data_array);
        return $stl;
    }

    public function list_stl($data)
    {
        if($data["type"] == "all")
        {
            $list = DB::table('stl_category')->select("id","title","image")->where("isDeleted",0)->get();
        }
        else
        {
            $list = DB::table('stl_category')->select("id","title","image")->where("id",$data["category"])->where("isDeleted",0)->get();
        }
        return $list;
    }

    public function stl_cat_update($id,$data) 
    {
        $update = DB::table('stl_category')->where("id",$id)->update($data);
        return $update;
    }
    
    public function add_stl_cat_products($catid,$data) 
    {
        
        
        
        $update = DB::table('stl_products')->where("catid",$catid)->delete();

        $i = 1;
        foreach($data["products"] as $pro)
        {
            $add_data["productid"] = $data["products"][$i]["productid"];
            $add_data["x_axis"] = $data["products"][$i]["x_axis"];
            $add_data["y_axis"] = $data["products"][$i]["y_axis"];
            $add_data["catid"] = $catid;
            $stl = DB::table('stl_products')->insertGetId($add_data);
        }

        if($stl)
        {
            $update = DB::table('stl_cat_images')->where("catid",$catid)->delete();

            for($i = 0;$i<=count($data["images"])-1;$i++)
            {
                $imagedata["image"] = $data["images"][$i];
                $imagedata["catid"] = $catid;
                $stl_image = DB::table('stl_cat_images')->insertGetId($imagedata);
            }
        }

        return $stl_image;
    }


    public function list_stl_cat_products($catid) 
    {       
        $stl_product = DB::table('stl_products')->where("catid",$catid)->get();
        $stl_cat_image = DB::table('stl_cat_images')->where("catid",$catid)->pluck('image')->toArray();
        
        $result["stl_product"] = $stl_product;
        $result["stl_cat_image"] = $stl_cat_image;
        
        return $result;
    }

}
