<?php
declare(strict_types=1);

namespace App\Service\DataTransformer;

use App\Exception\NotSupportedFormatException;
use App\Service\DataTransformer\CsvToArray\CsvToArrayTransformer;
use App\Service\DataTransformer\JsonToArray\JsonToArrayTransformerInterface;

class DataTransformer implements DataTransformerInterface
{
    /**
     * @var JsonToArrayTransformerInterface
     */
    private $jsonToArrayTransformer;

    /**
     * @var CsvToArrayTransformer
     */
    private $csvToArrayTransformer;

    /**
     * DataTransformer constructor.
     * @param JsonToArrayTransformerInterface $jsonToArrayTransformer
     * @param CsvToArrayTransformer $csvToArrayTransformer
     */
    public function __construct(JsonToArrayTransformerInterface $jsonToArrayTransformer, CsvToArrayTransformer $csvToArrayTransformer)
    {
        $this->jsonToArrayTransformer = $jsonToArrayTransformer;
        $this->csvToArrayTransformer = $csvToArrayTransformer;
    }

    /**
     * @param string $format
     * @param string $data
     * @return array
     * @throws NotSupportedFormatException
     */
    public function execute(string $format, string $data): ?array
    {
        switch ($format) {
            case self::JSON_TYPE:
                return $this->jsonToArrayTransformer->execute($data);
            case 'csv':
                return $this->csvToArrayTransformer->execute($data);
            default:
                throw new NotSupportedFormatException();
        }
    }
}