<?php


namespace App\Tests\Services;


use App\Services\Discounter;
use PHPUnit\Framework\TestCase;

class DiscounterTest extends TestCase
{
    /**
     * @dataProvider get_discount_tests
     */
    public function test_valid_calculate($productDiscount , $catDiscount , $expected){

        $discount = (new Discounter())->calculate($productDiscount , $catDiscount);

        $this->assertEquals($expected , $discount);

    }
    public function get_discount_tests(){
        return [
            //$productDiscount , $catDiscount , $expected
            [10,20,20],
            [30,20,30],
            [0,0,null]
        ];
    }

    /**
     * @dataProvider get_apply_tests
     */
    public function test_valid_apply($price , $discountValue , $type , $expected){

        if(empty($type))
            $price = (new Discounter())->apply($price , $discountValue);
        else
            $price = (new Discounter())->apply($price , $discountValue , $type);

        $this->assertEquals($expected , $price);

    }
    public function get_apply_tests(){
        return [
            //$price , $discountValue , $type , $expected
            [300,10,'',270],
            [350,20,'',280],
            [673,23,'',518.21],
            [673,23,'something',673],

        ];
    }








}