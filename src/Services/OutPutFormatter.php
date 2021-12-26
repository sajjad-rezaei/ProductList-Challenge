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
        foreach ($products AS $product){
            /** @var Product $product */
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

            $discount = $this->discounter->calculate($product->getDiscount() , $product->getCategory()->getDiscount());
            $finalPrice = $this->discounter->apply($finalPrice , $discount);


            $tempData['price']['discount_percentage'] = $discount;
            $tempData['price']['final'] = $finalPrice;

            $response[] = $tempData;

        }
        return $response;

    }
}