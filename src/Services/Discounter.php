<?php


namespace App\Services;


class Discounter
{


    public function calculate($productDiscount , $catDiscount ){

        //choose the bigger discount
        $discount = ($productDiscount > $catDiscount)? $productDiscount : $catDiscount;
        //both discounts can be zero so lets check it
        if($discount > 0)
            return $discount;
        else
            return null;

    }
    public function apply($price , $discountValue , $type = "percent"){
        //if discount was integer we apply it
        if(is_int($discountValue))
            switch ($type){
            //we may have another type later
                case "percent":
                    //simple discount calculation
                    $price = $price - ($price * ($discountValue/100));
                    break;
            }
        return $price;

    }
}