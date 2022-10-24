<?php
declare(strict_types=1);

namespace App\Service\DataTransformer\JsonToArray;

class JsonToArrayTransformer implements JsonToArrayTransformerInterface
{
   public function execute(string $data): array
   {
       return json_decode($data, true);
   }
}