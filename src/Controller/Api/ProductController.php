<?php

namespace App\Controller\Api;

use App\Entity\Product;
use App\Exception\WrongArgumentException;
use App\Repository\Product\ProductRepositoryInterface;
use App\Service\DataTransformer\DataTransformerInterface;
use App\Service\Product\ProductCreatorInterface;
use App\Service\Product\ProductRemoverInterface;
use App\Service\Product\ProductUpdaterInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ProductController
 *
 * @package App\Controller\Api
 *
 * @Route("api/products")
 */
class ProductController extends AbstractController
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * ProductController constructor.
     * @param DataTransformerInterface $dataTransformer
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @Route("/", methods={"GET"})
     * @param ProductRepositoryInterface $productRepository
     * @return JsonResponse
     */
    public function index(ProductRepositoryInterface $productRepository): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->serialize($productRepository->findAll(), "json", ["groups" => "list_product"]),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @param $product
     * @return JsonResponse
     *
     * @Route("/{id}", methods={"GET"}, requirements={"id": "[1-9]\d*"})
     */
    public function show(Product $product): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->serialize($product, "json", ["groups" => "show_product"]),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @param Request $request
     * @param ProductCreatorInterface $productCreator
     * @param DataTransformerInterface $dataTransformer
     * @return JsonResponse
     *
     * @Route("/post", methods={"POST"})
     */
    public function create(Request $request, ProductCreatorInterface $productCreator, DataTransformerInterface $dataTransformer): JsonResponse
    {
        try {
            $body = $request->getContent();

            $data = $dataTransformer->execute(DataTransformerInterface::JSON_TYPE, $body);

            $productCreator->execute($data);

            return new JsonResponse('Product created!', Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return new JsonResponse('Error, cannot create new product.', Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param int $id
     * @param Request $request
     * @param ProductUpdaterInterface $productUpdater
     * @param DataTransformerInterface $dataTransformer
     * @return JsonResponse
     *
     * @Route("/{id}", methods={"PUT"})
     */
    public function update(int $id, Request $request, ProductUpdaterInterface $productUpdater, DataTransformerInterface $dataTransformer): JsonResponse
    {
        try {
            $body = $request->getContent();

            $data = $dataTransformer->execute('json', $body);

            $productUpdater->execute($id, $data);

            return new JsonResponse('Product updated!', Response::HTTP_OK);
        } catch (WrongArgumentException $wrongArgumentException) {
            return new JsonResponse('Error, cannot find product with this id.', Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return new JsonResponse('Error, cannot update new product.', Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param int $id
     * @param ProductRemoverInterface $remover
     * @return JsonResponse
     *
     * @Route("/{id}", methods={"DELETE"})
     */
    public function delete(int $id, ProductRemoverInterface $remover): JsonResponse
    {
        try {
            $remover->execute($id);

            return new JsonResponse('Product deleted', JsonResponse::HTTP_ACCEPTED);
        } catch (WrongArgumentException $wrongArgumentException) {
            return new JsonResponse('Error, cannot find product with this id.', Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return new JsonResponse('Error, cannot delete product.', JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}