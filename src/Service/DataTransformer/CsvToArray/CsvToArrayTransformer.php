<?php
declare(strict_types=1);

namespace App\Service\DataTransformer\CsvToArray;

use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class CsvToArrayTransformer implements CsvToArrayTransformerInterface
{
    /**
     * @var Serializer
     */
    private $serializer;

    public function __construct()
    {
        $this->serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
    }

    public function execute(string $data): array
    {
        return $this->serializer->decode($data, 'csv');
    }
}