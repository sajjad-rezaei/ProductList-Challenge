<?php

namespace App\Controller;


use App\Entity\Product;
use App\Repository\ProductRepository;

use App\Services\OutPutFormatter;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/list", name="productList")
     */
    public function index(OutPutFormatter  $outPutFormatter ,ProductRepository $productRepository   , Request  $request): Response
    {


        //get the request params
        $category = $request->query->get("category");
        $priceLessThan =  $request->query->get('priceLessThan');
        $page =  $request->query->get('page');
        if(!$page)
            $page = 0;

        //get list of products
        $products =  $productRepository->findBySomeField(
            ['category' => $category , "priceLessThan" => $priceLessThan , "page" => $page]);


        //return the json response with data formatted
        return $this->json(
            $outPutFormatter->format($products)
        );

    }
}
