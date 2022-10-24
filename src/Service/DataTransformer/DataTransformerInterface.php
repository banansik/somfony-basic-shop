<?php
declare(strict_types=1);

namespace App\Service\DataTransformer;

use App\Exception\NotSupportedFormatException;

interface DataTransformerInterface
{
    public const JSON_TYPE = 'json';

    /**
     * @param string $format
     * @param string $data
     * @return array
     * @throws NotSupportedFormatException
     */
    public function execute(string $format, string $data): ?array;
}