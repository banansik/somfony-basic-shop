<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 *
 * @ORM\Table(
 *     name="image",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="UQ_filename_1",
 *              columns={"filename"},
 *          ),
 *     },
 * )
 *
 * @UniqueEntity(
 *     fields={"filename"},
 * )
 */
class Image
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="images",cascade={"persist"})
     *
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     *
     * @Assert\Type(type="App\Entity\Product")
     */
    private $product;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Type(type="string")
     */
    private $filename;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $main;


    private $file;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): void
    {
        $this->product = $product;
    }

    public function getFileName(): ?string
    {
        return $this->filename;
    }

    public function setFileName(string $filename): void
    {
        $this->filename = $filename;
    }

    public function getMain(): ?bool
    {
        return $this->main;
    }

    public function setMain($main): void
    {
        $this->main = $main;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $files
     */
    public function setFile($file): void
    {
        $this->file = $file;
    }
}
