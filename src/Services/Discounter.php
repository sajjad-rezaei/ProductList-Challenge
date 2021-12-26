<?php


namespace App\Services;


class Discounter
{


    public function calculate($productDiscount , $catDiscount ){


        $discount = ($productDiscount > $catDiscount)? $productDiscount : $catDiscount;
        if($discount > 0)
            return $discount;
        else
            return null;

    }
    public function apply($price , $discountValue , $type = "percent"){

        if(is_int($discountValue))
            switch ($type){
                case "percent":
                    $price = $price - ($price * ($discountValue/100));
                    break;
            }
        return $price;

    }
}