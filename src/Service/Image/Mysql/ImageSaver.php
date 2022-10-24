<?php
declare(strict_types=1);

namespace App\Service\Image\Mysql;

use App\Exception\Image\MainImageLimitException;
use App\Repository\ImageRepository;
use App\Service\File\Local\FileUploader;
use App\Service\Image\ImageSaverInterface;

class ImageSaver implements ImageSaverInterface
{
    /**
     * @var ImageRepository
     */
    private $imageRepository;

    /**
     * @var FileUploader
     */
    private $fileUploader;

    /**
     * ImageSaver constructor.
     * @param ImageRepository $imageRepository
     * @param FileUploader $fileUploader
     */
    public function __construct(ImageRepository $imageRepository, FileUploader $fileUploader)
    {
        $this->imageRepository = $imageRepository;
        $this->fileUploader = $fileUploader;
    }

    public function execute(array $images): void
    {
        if ($this->isMainImageLimitReached($images)) {
            throw new MainImageLimitException('You can choose only one main image for product');
        }

        $this->setFileNames($images);

        $this->imageRepository->saveImages($images);
    }

    private function isMainImageLimitReached(array $images): bool
    {
        $main_image_counter = 0;

        foreach ($images as $image) {
            if ($image->getMain()) {
                $main_image_counter++;
            }
        }

        return ($main_image_counter > 1);
    }

    /**
     * @param array $images
     */
    private function setFileNames(array $images): void
    {
        if (count($images) > 0) {
            foreach ($images as $image) {

                $files = $image->getFile();

                foreach ($files as $file) {
                    $imageFilename = $this->fileUploader->execute($file);
                    $image->setFileName($imageFilename);
                }
            }
        }
    }
}