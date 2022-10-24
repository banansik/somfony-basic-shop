<?php
/**
 * File uploader.
 */

namespace App\Service\File\Local;

use App\Service\File\FileUploaderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class FileUploader.
 */
class FileUploader implements FileUploaderInterface
{
    /**
     * Target directory.
     *
     * @var string $targetDirectory
     */
    private $targetDirectory;

    /**
     * FileUploader constructor.
     *
     * @param string $targetDirectory Target director
     */
    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * Upload file.
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file File to upload
     *
     * @return string Filename of uploaded file
     */
    public function execute(UploadedFile $file): string
    {
        $fileName = $this->generateFilename(
            pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            $file->guessClientExtension()
        );

        $file->move($this->getTargetDirectory(), $fileName);

        return $fileName;
    }

    /**
     * Getter for target directory.
     *
     * @return string Target directory
     */
    private function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }

    /**
     * Generates new filename.
     *
     * @param string $originalFilename Original filename
     * @param string $extension        File extension
     *
     * @return string New filename
     */
    private function generateFilename(string $originalFilename, string $extension): string
    {
        $safeFilename = transliterator_transliterate(
            'Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()',
            $originalFilename
        );

        return $safeFilename.'-'.uniqid('', true).'.'.$extension;
    }
}