<?php
declare(strict_types=1);

namespace App\Service\Image;

interface ImageSaverInterface
{
    public function execute(array $images);
}