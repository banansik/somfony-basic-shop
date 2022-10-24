<?php
declare(strict_types=1);

namespace App\Service\DataTransformer\CsvToArray;

interface CsvToArrayTransformerInterface
{
    /**
     * Transform csv string to array.
     *
     * @param string $data
     * @return array
     */
    public function execute(string $data): array;
}