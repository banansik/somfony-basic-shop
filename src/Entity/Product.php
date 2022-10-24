<?php

namespace App\Entity;

use App\Repository\Product\Mysql\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"list_product", "show_product"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="50",
     * )
     * @Groups({"list_product", "show_product"})
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Assert\Type(type="string")
     * @Groups({"show_product"})
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\Type(type="\DateTimeInterface")
     *
     * @Gedmo\Timestampable(on="create")
     * @Groups({"show_product"})
     *
     * @Groups({"show_product"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     *
     *  @Assert\Type(type="\DateTimeInterface")
     *
     * @Gedmo\Timestampable(on="update")
     * @Groups({"show_product"})
     *
     * @Groups({"show_product"})
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(
     *     targetEntity=Category::class,
     *     inversedBy="products"
     * )
     * @ORM\JoinTable(name="products_categories")
     * @Groups({"show_product"})
     *
     * @Groups({"show_product"})
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="product", orphanRemoval=true, cascade={"persist"})
     */
    private $images;

    /**
     * Product constructor.
     */
    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface $createdAt
     */
    public function setCreatedAt(DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTimeInterface
     */
    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTimeInterface $updatedAt
     */
    public function setUpdatedAt(DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return Collection
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    /**
     * @param Category $category
     */
    public function addCategory(Category $category): void
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }
    }
    public function addCategories($categories)
    {
        foreach ($categories as $category) {
            $this->addCategory($category);
        }
    }

    /**
     * @param Category $category
     */
    public function removeCategory(Category $category): void
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }
    }
    /**
     * @return Collection|images[]
     */
    public function getImages()
    {
        return $this->images;
    }

    public function addImage(Image $image): void
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setProduct($this);
        }
    }

    public function removeImage(Image $image): void
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            if ($image->getProduct() === $this) {
                $image->setProduct(null);
            }
        }
    }
}
