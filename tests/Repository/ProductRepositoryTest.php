<?php


namespace App\Tests\Repository;


use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class ProductRepositoryTest extends KernelTestCase
{


    /** @var EntityManagerInterface */
    private $entityManager;

    protected function setUp():void
    {

        $kernel = self::bootKernel([
            'environment' => 'test',
            'debug'       => false,
        ]);

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }
    protected function tearDown():void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
    public function test_find_By_some_Field_return(){

        $products = $this->entityManager->getRepository(Product::class)->findBySomeField([]);

        $this->assertNotEquals( 0 , count($products));

    }
    public function test_find_By_some_Field_not_return(){

        $products = $this->entityManager->getRepository(Product::class)->findBySomeField(["category" => "boots1123s"]);

        $this->assertEquals( 0 , count($products));

    }
    public function test_find_By_some_Field_return_just_category(){

        $products = $this->entityManager->getRepository(Product::class)->findBySomeField(["category" => "boots"]);
        /** @var Product $product */
        foreach ($products AS $product){
            $this->assertEquals($product->getCategory()->getName() , "boots");
        }

    }
    public function test_find_By_some_Field_return_just_priceLessThat(){

        $products = $this->entityManager->getRepository(Product::class)->findBySomeField(["priceLessThan" => 79500]);
        /** @var Product $product */
        foreach ($products AS $product){

            $this->assertLessThanOrEqual(  79500 , $product->getPrice());
        }

    }
    public function test_find_By_some_Field_return_both_param(){

        $products = $this->entityManager->getRepository(Product::class)->findBySomeField(["priceLessThan" => 79500 , "category" => "boots"]);
        /** @var Product $product */
        foreach ($products AS $product){
            $this->assertEquals($product->getCategory()->getName() , "boots");
            $this->assertLessThanOrEqual(  79500 , $product->getPrice());
        }
        $this->assertNotEquals( 0 , count($products));

    }
}