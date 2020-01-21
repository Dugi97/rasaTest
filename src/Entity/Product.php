<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="products")
     */
    private $created_by;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BannedProduct", mappedBy="product_id")
     */
    private $bannedProducts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductRating", mappedBy="product_id")
     */
    private $productRatings;

    public function __construct()
    {
        $this->bannedProducts = new ArrayCollection();
        $this->productRatings = new ArrayCollection();
        $this->setCreatedAt(new \DateTime('now'));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->created_by;
    }

    public function setCreatedBy(?User $created_by): self
    {
        $this->created_by = $created_by;

        return $this;
    }

    /**
     * @return Collection|BannedProduct[]
     */
    public function getBannedProducts(): Collection
    {
        return $this->bannedProducts;
    }

    public function addBannedProduct(BannedProduct $bannedProduct): self
    {
        if (!$this->bannedProducts->contains($bannedProduct)) {
            $this->bannedProducts[] = $bannedProduct;
            $bannedProduct->setProductId($this);
        }

        return $this;
    }

    public function removeBannedProduct(BannedProduct $bannedProduct): self
    {
        if ($this->bannedProducts->contains($bannedProduct)) {
            $this->bannedProducts->removeElement($bannedProduct);
            // set the owning side to null (unless already changed)
            if ($bannedProduct->getProductId() === $this) {
                $bannedProduct->setProductId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProductRating[]
     */
    public function getProductRatings(): Collection
    {
        return $this->productRatings;
    }

    public function addProductRating(ProductRating $productRating): self
    {
        if (!$this->productRatings->contains($productRating)) {
            $this->productRatings[] = $productRating;
            $productRating->setProductId($this);
        }

        return $this;
    }

    public function removeProductRating(ProductRating $productRating): self
    {
        if ($this->productRatings->contains($productRating)) {
            $this->productRatings->removeElement($productRating);
            // set the owning side to null (unless already changed)
            if ($productRating->getProductId() === $this) {
                $productRating->setProductId(null);
            }
        }

        return $this;
    }
}
