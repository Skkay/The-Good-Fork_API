<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\OrderRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;

/**
 * @ApiResource(
 *      attributes={
 *          "order"={
 *              "id": "DESC"
 *          }
 *      },
 *      normalizationContext={
 *          "groups"="order:read"
 *      },
 *      denormalizationContext={
 *          "groups"="order:write"
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
 *              "security"="is_granted('ROLE_ADMIN') or object.getUser() == user", 
 *              "path"="/orders/{id}"
 *          },
 *          "put"={
 *              "security"="is_granted('ROLE_ADMIN') or object.getUser() == user", 
 *              "path"="/orders/{id}"
 *          },
 *          "delete"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "path"="/orders/{id}"
 *          },
 *          "patch"={
 *              "security"="is_granted('ROLE_ADMIN') or object.getUser() == user", 
 *              "path"="/orders/{id}"
 *          }
 *      }
 * )
 * @ApiFilter(
 *      NumericFilter::class,
 *      properties={"status.id"}
 * )
 * @ApiFilter(BooleanFilter::class, properties={"eatIn"})
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"order:read", "user:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"order:read", "order:write", "user:read"})
     */
    private $eatIn;

    /**
     * @ORM\Column(type="float")
     * @Groups({"order:read", "user:read"})
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"order:read", "user:read"})
     */
    private $date_order;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"order:read", "user:read"})
     */
    private $date_payment;

    /**
     * @ORM\ManyToOne(targetEntity=OrderStatus::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"order:read", "user:read"})
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders")
     * @Groups("order:read")
     */
    private $user;

    /**
     * @Groups("order:write")
     */
    private $menuIds;

    /**
     * @Groups("order:write")
     */
    private $foodIds;

    /**
     * @Groups("order:write")
     */
    private $drinkIds;

    /**
     * @ORM\OneToMany(targetEntity=OrderMenu::class, mappedBy="order_", orphanRemoval=true, cascade="persist")
     * @Groups("order:read")
     */
    private $orderedMenu;

    /**
     * @ORM\OneToMany(targetEntity=OrderFood::class, mappedBy="order_", orphanRemoval=true, cascade="persist")
     * @Groups("order:read")
     */
    private $orderedFood;

    /**
     * @ORM\OneToMany(targetEntity=OrderDrink::class, mappedBy="order_", orphanRemoval=true, cascade="persist")
     * @Groups("order:read")
     */
    private $orderedDrink;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"order:read", "order:write", "user:read"})
     */
    private $datePickup;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"order:read", "order:write", "user:read"})
     */
    private $extraInformations;

    /**
     * @Groups("order:write")
     */
    private $discountId;

    /**
     * @ORM\OneToOne(targetEntity=Reservation::class, inversedBy="order_", cascade={"persist", "remove"})
     * @Groups("order:read")
     */
    private $reservation;

    /**
     * @Groups("order:write")
     */
    private $reservationId;

    /**
     * @Groups("order:write")
     */
    private $orderedByStaff;

    public function __construct()
    {
        $this->menuIds = [];
        $this->foodIds = [];
        $this->drinkIds = [];
        $this->orderedMenu = new ArrayCollection();
        $this->orderedFood = new ArrayCollection();
        $this->orderedDrink = new ArrayCollection();
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

    /**
     * @return Collection|OrderMenu[]
     */
    public function getOrderedMenu(): Collection
    {
        return $this->orderedMenu;
    }

    public function addOrderedMenu(OrderMenu $orderedMenu): self
    {
        if (!$this->orderedMenu->contains($orderedMenu)) {
            $this->orderedMenu[] = $orderedMenu;
            $orderedMenu->setOrder($this);
        }

        return $this;
    }

    public function removeOrderedMenu(OrderMenu $orderedMenu): self
    {
        if ($this->orderedMenu->removeElement($orderedMenu)) {
            // set the owning side to null (unless already changed)
            if ($orderedMenu->getOrder() === $this) {
                $orderedMenu->setOrder(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|OrderFood[]
     */
    public function getOrderedFood(): Collection
    {
        return $this->orderedFood;
    }

    public function addOrderedFood(OrderFood $orderedFood): self
    {
        if (!$this->orderedFood->contains($orderedFood)) {
            $this->orderedFood[] = $orderedFood;
            $orderedFood->setOrder($this);
        }

        return $this;
    }

    public function removeOrderedFood(OrderFood $orderedFood): self
    {
        if ($this->orderedFood->removeElement($orderedFood)) {
            // set the owning side to null (unless already changed)
            if ($orderedFood->getOrder() === $this) {
                $orderedFood->setOrder(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|OrderDrink[]
     */
    public function getOrderedDrink(): Collection
    {
        return $this->orderedDrink;
    }

    public function addOrderedDrink(OrderDrink $orderedDrink): self
    {
        if (!$this->orderedDrink->contains($orderedDrink)) {
            $this->orderedDrink[] = $orderedDrink;
            $orderedDrink->setOrder($this);
        }

        return $this;
    }

    public function removeOrderedDrink(OrderDrink $orderedDrink): self
    {
        if ($this->orderedDrink->removeElement($orderedDrink)) {
            // set the owning side to null (unless already changed)
            if ($orderedDrink->getOrder() === $this) {
                $orderedDrink->setOrder(null);
            }
        }

        return $this;
    }

    public function getDatePickup(): ?\DateTimeInterface
    {
        return $this->datePickup;
    }

    public function setDatePickup(?\DateTimeInterface $datePickup): self
    {
        $this->datePickup = $datePickup;

        return $this;
    }

    public function getExtraInformations(): ?string
    {
        return $this->extraInformations;
    }

    public function setExtraInformations(?string $extraInformations): self
    {
        $this->extraInformations = $extraInformations;

        return $this;
    }

    public function setDiscountId(int $discountId): self
    {
        $this->discountId = $discountId;

        return $this;
    }

    public function getDiscountId(): ?int
    {
        return $this->discountId;
    }

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(?Reservation $reservation): self
    {
        $this->reservation = $reservation;

        return $this;
    }

    public function setReservationId(int $reservationId): self
    {
        $this->reservationId = $reservationId;

        return $this;
    }

    public function getReservationId(): ?int
    {
        return $this->reservationId;
    }

    public function setOrderedByStaff(bool $orderedByStaff): self
    {
        $this->orderedByStaff = $orderedByStaff;

        return $this;
    }

    public function getOrderedByStaff(): ?bool
    {
        return $this->orderedByStaff;
    }
}
