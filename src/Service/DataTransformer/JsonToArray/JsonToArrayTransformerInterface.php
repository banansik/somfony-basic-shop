<?php
declare(strict_types=1);

namespace App\Service\DataTransformer\JsonToArray;

interface JsonToArrayTransformerInterface
{
    /**
     * Transform json string to array.
     *
     * @param string $data
     * @return array
     */
    public function execute(string $data): array;
}