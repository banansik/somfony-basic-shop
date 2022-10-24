<?php

namespace App\Repository\Product\Mysql;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\Product\ProductRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository implements ProductRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function queryAllProductsByName()
    {
        return $this->createQueryBuilder('product')
            ->select('product.name')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param array $ids
     * @return array
     */
    public function findProductsByNames(array $names): array
    {
        return $this->createQueryBuilder("product")
            ->where("product.name IN (:names)")
            ->setParameter('names', $names)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param array $ids
     * @return array
     */
    public function findProductsNames(array $names): array
    {
        return $this->createQueryBuilder("product")
            ->select('product.name')
            ->where("product.name IN (:names)")
            ->setParameter('names', $names)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param array $ids
     * @return array
     */
    public function findProductsByIds(array $ids): array
    {
        return $this->createQueryBuilder("product")
            ->where("product.id IN (:ids)")
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();
    }

    public function save(Product $product): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($product);
        $entityManager->flush($product);
    }

    public function delete(Product $product): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($product);
        $entityManager->flush($product);
    }
}
