<?php


namespace App\Services;


use App\Entity\Product;

class OutPutFormatter
{
    public $discounter;
    public function __construct(Discounter  $discounter)
    {
        $this->discounter = $discounter;
    }

    public function format($products){


        $response = [];
        //lets format products one by one
        foreach ($products AS $product){
            /** @var Product $product */
            //this is the general format
            $tempData = [
                "sku"  => $product->getSku(),
                "name" => $product->getName(),
                "category" =>  $product->getCategory()->getName(),
                "price" => [
                    "original" => $product->getPrice(),
                    "currency" =>  "EUR"
                ]
            ];
            $finalPrice = $product->getPrice();

            //use discounter to choose the right discount
            $discount = $this->discounter->calculate($product->getDiscount() , $product->getCategory()->getDiscount());
            //use discounter to apply the discount
            $finalPrice = $this->discounter->apply($finalPrice , $discount);

            //need them also in data array
            $tempData['price']['discount_percentage'] = $discount;
            $tempData['price']['final'] = $finalPrice;

            $response[] = $tempData;

        }
        return $response;

    }
}