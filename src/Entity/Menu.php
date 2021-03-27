<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MenuRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      normalizationContext={
 *          "groups"="read"
 *      },
 *      denormalizationContext={
 *          "groups"="write"
 *      },
 *      collectionOperations={
 *          "get"={
 *              "security"="is_granted('ROLE_USER')", 
 *              "path"="/menus"
 *          },
 *          "post"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "path"="/menus"
 *          }
 *      },
 *      itemOperations={
 *          "get"={
 *              "security"="is_granted('ROLE_USER')", 
 *              "path"="/menus/{id}"
 *          },
 *          "put"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "path"="/menus/{id}"
 *          },
 *          "delete"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "path"="/menus/{id}"
 *          },
 *          "patch"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "path"="/menus/{id}"
 *          }
 *      }
 * )
 * @ORM\Entity(repositoryClass=MenuRepository::class)
 */
class Menu
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("read")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Drink::class, cascade="persist")
     * @Groups({"read", "write"})
     */
    private $drinks;

    /**
     * @ORM\ManyToMany(targetEntity=Food::class, cascade="persist")
     * @Groups({"read", "write"})
     */
    private $foods;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"read", "write"})
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     * @Groups({"read", "write"})
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    public function __construct()
    {
        $this->drinks = new ArrayCollection();
        $this->foods = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Drink[]
     */
    public function getDrinks(): Collection
    {
        return $this->drinks;
    }

    public function addDrink(Drink $drink): self
    {
        if (!$this->drinks->contains($drink)) {
            $this->drinks[] = $drink;
        }

        return $this;
    }

    public function removeDrink(Drink $drink): self
    {
        $this->drinks->removeElement($drink);

        return $this;
    }

    /**
     * @return Collection|Food[]
     */
    public function getFoods(): Collection
    {
        return $this->foods;
    }

    public function addFood(Food $food): self
    {
        if (!$this->foods->contains($food)) {
            $this->foods[] = $food;
        }

        return $this;
    }

    public function removeFood(Food $food): self
    {
        $this->foods->removeElement($food);

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

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
}
