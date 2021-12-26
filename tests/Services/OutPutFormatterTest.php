<?php


namespace App\Tests\Services;


use App\Entity\Product;
use App\Services\Discounter;
use App\Services\OutPutFormatter;
use PHPUnit\Framework\TestCase;

class OutPutFormatterTest extends TestCase
{

    /**
     * @dataProvider get_outPut_tests
     */
    public function test_valid_outPut($products   , $expected){

        $dormattedProduct = (new OutPutFormatter(new Discounter()))->format($products);



        $this->assertEqualsCanonicalizing($expected , $dormattedProduct);

    }
    public function get_outPut_tests(){
        $product1 = (new Product())->fill([
            "id" => 1,
            "name" => "some product 1",
            "sku" => "00001",
            "discount" => 0,
            "price" => 56000,
            "catId" => 1,
            "catName" => "boots",
            "catDiscount" => 30,
        ]);
        $expectedProduct1 = [
            "sku"  => "00001",
            "name" => "some product 1",
            "category" =>  "boots",
            "price" => [
                "original" => 56000,
                "final" => 39200,
                "discount_percentage" => 30,
                "currency" =>  "EUR"
            ]
        ];
        $product2 = (new Product())->fill([
            "id" => 2,
            "name" => "some product 2",
            "sku" => "00003",
            "discount" => 10,
            "price" => 84000,
            "catId" => 1,
            "catName" => "boots",
            "catDiscount" => 30,
        ]);
        $expectedProduct2 = [
            "sku"  => "00003",
            "name" => "some product 2",
            "category" =>  "boots",
            "price" => [
                "original" => 84000,
                "final" => 58800,
                "discount_percentage" => 30,
                "currency" =>  "EUR"
            ]
        ];
        $product3 = (new Product())->fill([
            "id" => 3,
            "name" => "some product 4",
            "sku" => "00005",
            "discount" => 20,
            "price" => 35000,
            "catId" => 3,
            "catName" => "shoes",
            "catDiscount" => 0,
        ]);
        $expectedProduct3 = [
            "sku"  => "00005",
            "name" => "some product 4",
            "category" =>  "shoes",
            "price" => [
                "original" => 35000,
                "final" => 28000,
                "discount_percentage" => 20,
                "currency" =>  "EUR"
            ]
        ];
        $product4 = (new Product())->fill([
            "id" => 3,
            "name" => "some product 5",
            "sku" => "00006",
            "discount" => 0,
            "price" => 54000,
            "catId" => 3,
            "catName" => "shoes2",
            "catDiscount" => 0,
        ]);
        $expectedProduct4 = [
            "sku"  => "00006",
            "name" => "some product 5",
            "category" =>  "shoes2",
            "price" => [
                "original" => 54000,
                "final" => 54000,
                "discount_percentage" => null,
                "currency" =>  "EUR"
            ]
        ];
        return [
            //$products   , $expected
            [ [$product1] ,[$expectedProduct1] ],
            [ [$product1,$product3] ,[$expectedProduct1,$expectedProduct3] ],
            [ [$product1,$product3,$product2,$product4] ,[$expectedProduct1,$expectedProduct2,$expectedProduct3,$expectedProduct4] ],

        ];
    }

}