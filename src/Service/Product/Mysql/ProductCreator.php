<?php
declare(strict_types=1);

namespace App\Service\Product\Mysql;

use App\Entity\Product;
use App\Repository\Category\CategoryRepositoryInterface;
use App\Service\Product\ProductCreatorInterface;
use App\Service\Product\ProductSaverInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ProductCreator implements ProductCreatorInterface
{
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @var ProductSaverInterface
     */
    private $productSaver;

    /**
     * @var Serializer 
     */
    private $serializer;

    /**
     * ProductCreator constructor.
     * @param CategoryRepositoryInterface $categoryRepository
     * @param ProductSaverInterface $productSaver
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository, ProductSaverInterface $productSaver)
    {
        $this->categoryRepository = $categoryRepository;
        $this->productSaver = $productSaver;
        $this->serializer = new Serializer([new ObjectNormalizer()]);
    }

    public function execute(array $data): void
    {
        [$categories, $data] = $this->getCategoriesIdsAndUnset($data);

        $product = $this->serializer->denormalize($data, Product::class);

        $this->addCategories($categories, $product);
        $this->productSaver->execute($product);
    }

    /**
     * @param $categories
     * @param $product
     */
    private function addCategories(array $categories, Product $product): void
    {
        $categories = $this->categoryRepository->findCategoriesByIdByIds($categories);

        $product->addCategories($categories);
    }

    /**
     * @param array $data
     * @return array
     */
    private function getCategoriesIdsAndUnset(array $data): array
    {
        $categories = $data['categories'];
        unset($data['categories']);

        return array($categories, $data);
    }
}