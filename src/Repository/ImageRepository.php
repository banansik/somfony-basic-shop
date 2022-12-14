<?php

namespace App\Repository;

use App\Entity\Image;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Image|null find($id, $lockMode = null, $lockVersion = null)
 * @method Image|null findOneBy(array $criteria, array $orderBy = null)
 * @method Image[]    findAll()
 * @method Image[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Image::class);
    }

    public function findImagesFilenames()
    {
        return $this->createQueryBuilder("image")
            ->select('image.filename')
            ->getQuery()
            ->getResult();

    }

    public function save(Image $image): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($image);
        $entityManager->flush($image);
    }

    public function saveImages($images)
    {
        foreach ($images as $image) {
            $this->save($image);
        }
    }

}
