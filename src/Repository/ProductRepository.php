<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }
     /**
      * @return Product[] Returns an array of Product objects
      */
    public function findBySomeField(array $values)
    {

        $where = "";
        $category = @$values['category'];
        $priceLessThan = @$values['priceLessThan'];
        $page = @$values['page'];
        $queryBuilderObj  = $this->createQueryBuilder('p')
            ->innerJoin('p.category', 'cat');

        if($category) {
            $where .= " cat.name = :categoryName AND";
            $queryBuilderObj = $queryBuilderObj->setParameter("categoryName", $category);
        }
        if($priceLessThan) {
            $where .= " p.price <= :price ";
            $queryBuilderObj = $queryBuilderObj->setParameter("price", $priceLessThan);
        }
        $where = rtrim($where , "AND");
        if(!empty($category) OR !empty($priceLessThan))
            $queryBuilderObj =
                $queryBuilderObj->where(
                    $where
                );
        $queryBuilderObj = $queryBuilderObj->orderBy('p.id', 'DESC')
            ->setFirstResult($page*5)
            ->setMaxResults( 5 );
        $query =   $queryBuilderObj->getQuery();

        return $query->getResult();

    }




    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
