<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\OrderRepository;
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
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "path"="/orders"
 *          },
 *          "post"={
 *              "security"="is_granted('ROLE_USER')", 
 *              "path"="/orders"
 *          }
 *      },
 *      itemOperations={
 *          "get"={
 *              "security"="is_granted('ROLE_ADMIN') or object.getUser().getId() == user.getId()", 
 *              "path"="/orders/{id}"
 *          },
 *          "put"={
 *              "security"="is_granted('ROLE_ADMIN') or object.getUser().getId() == user.getId()", 
 *              "path"="/orders/{id}"
 *          },
 *          "delete"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "path"="/orders/{id}"
 *          },
 *          "patch"={
 *              "security"="is_granted('ROLE_ADMIN') or object.getUser().getId() == user.getId()", 
 *              "path"="/orders/{id}"
 *          }
 *      }
 * )
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("read")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"read", "write"})
     */
    private $eatIn;

    /**
     * @ORM\Column(type="float")
     * @Groups("read")
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("read")
     */
    private $date_order;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups("read")
     */
    private $date_payment;

    /**
     * @ORM\ManyToMany(targetEntity=Menu::class)
     * @Groups("read")
     */
    private $menus;

    /**
     * @ORM\ManyToMany(targetEntity=Food::class)
     * @Groups("read")
     */
    private $foods;

    /**
     * @ORM\ManyToMany(targetEntity=Drink::class)
     * @Groups("read")
     */
    private $drinks;

    /**
     * @ORM\ManyToOne(targetEntity=OrderStatus::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("read")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("read")
     */
    private $user;

    /**
     * @Groups("write")
     */
    private $menuIds;

    /**
     * @Groups("write")
     */
    private $foodIds;

    /**
     * @Groups("write")
     */
    private $drinkIds;

    public function __construct()
    {
        $this->menus = new ArrayCollection();
        $this->foods = new ArrayCollection();
        $this->drinks = new ArrayCollection();

        $this->menuIds = [];
        $this->foodIds = [];
        $this->drinkIds = [];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEatIn(): ?bool
    {
        return $this->eatIn;
    }

    public function setEatIn(bool $eatIn): self
    {
        $this->eatIn = $eatIn;

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

    public function getDateOrder(): ?\DateTimeInterface
    {
        return $this->date_order;
    }

    public function setDateOrder(\DateTimeInterface $date_order): self
    {
        $this->date_order = $date_order;

        return $this;
    }

    public function getDatePayment(): ?\DateTimeInterface
    {
        return $this->date_payment;
    }

    public function setDatePayment(?\DateTimeInterface $date_payment): self
    {
        $this->date_payment = $date_payment;

        return $this;
    }

    /**
     * @return Collection|Menu[]
     */
    public function getMenus(): Collection
    {
        return $this->menus;
    }

    public function addMenu(Menu $menu): self
    {
        if (!$this->menus->contains($menu)) {
            $this->menus[] = $menu;
        }

        return $this;
    }

    public function removeMenu(Menu $menu): self
    {
        $this->menus->removeElement($menu);

        return $this;
    }

    /**
     * @return Collection|Food[]
     */
    public function getFoods(): Collection
    {
        return $this->foods;
    }

    public function addFood(Food $foods): self
    {
        if (!$this->foods->contains($foods)) {
            $this->foods[] = $foods;
        }

        return $this;
    }

    public function removeFood(Food $foods): self
    {
        $this->foods->removeElement($foods);

        return $this;
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

    public function getStatus(): ?OrderStatus
    {
        return $this->status;
    }

    public function setStatus(?OrderStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getMenuIds(): ?array
    {
        return $this->menuIds;
    }

    public function setMenuIds(array $menuId): self
    {
        $this->menuIds = $menuId;

        return $this;
    }

    public function getFoodIds(): ?array
    {
        return $this->foodIds;
    }

    public function setFoodIds(array $foodId): self
    {
        $this->foodIds = $foodId;

        return $this;
    }

    public function getDrinkIds(): ?array
    {
        return $this->drinkIds;
    }

    public function setDrinkIds(array $drinkId): self
    {
        $this->drinkIds = $drinkId;

        return $this;
    }
}
