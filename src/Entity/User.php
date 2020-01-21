<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string")
     */
    private $password = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="created_by")
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BannedProduct", mappedBy="user_id")
     */
    private $bannedProducts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductRating", mappedBy="user_id")
     */
    private $productRatings;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->bannedProducts = new ArrayCollection();
        $this->productRatings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword()
    {
        // not needed for apps that do not check user passwords
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed for apps that do not check user passwords
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setCreatedBy($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getCreatedBy() === $this) {
                $product->setCreatedBy(null);
            }
        }

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
            $bannedProduct->setUserId($this);
        }

        return $this;
    }

    public function removeBannedProduct(BannedProduct $bannedProduct): self
    {
        if ($this->bannedProducts->contains($bannedProduct)) {
            $this->bannedProducts->removeElement($bannedProduct);
            // set the owning side to null (unless already changed)
            if ($bannedProduct->getUserId() === $this) {
                $bannedProduct->setUserId(null);
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
            $productRating->setUserId($this);
        }

        return $this;
    }

    public function removeProductRating(ProductRating $productRating): self
    {
        if ($this->productRatings->contains($productRating)) {
            $this->productRatings->removeElement($productRating);
            // set the owning side to null (unless already changed)
            if ($productRating->getUserId() === $this) {
                $productRating->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}
