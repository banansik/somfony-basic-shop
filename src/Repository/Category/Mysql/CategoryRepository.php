<?php

namespace App\Repository\Category\Mysql;

use App\Entity\Category;
use App\Repository\Category\CategoryRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository implements CategoryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @param array $ids
     * @return array
     */
    public function findCategoriesByIdByIds(array $ids): array
    {
        return $this->createQueryBuilder("category")
            ->where("category.id IN (:ids)")
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();
    }

    public function save(Category $category): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($category);
        $entityManager->flush($category);
    }

    public function delete(Category $category): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($category);
        $entityManager->flush($category);
    }
}
