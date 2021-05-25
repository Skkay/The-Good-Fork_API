<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReservationRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\AvailableTablesController;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;

/**
 * @ApiResource(
 *      attributes={
 *          "order"={
 *              "date": "ASC"
 *          }
 *      },
 *      normalizationContext={
 *          "groups"="reservation:read"
 *      },
 *      denormalizationContext={
 *          "groups"="reservation:write"
 *      },
 *      collectionOperations={
 *          "get"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "path"="/reservations"
 *          },
 *          "get_available_tables"={
 *              "method"="GET",
 *              "security"="is_granted('ROLE_USER')", 
 *              "path"="/available_tables",
 *              "controller"=AvailableTablesController::class
 *          },
 *          "post"={
 *              "security"="is_granted('ROLE_USER')", 
 *              "path"="/reservations"
 *          },
 *      },
 *      itemOperations={
 *          "get"={
 *              "security"="is_granted('ROLE_ADMIN') or object.getUser() == user", 
 *              "path"="/reservations/{id}"
 *          },
 *          "put"={
 *              "security"="is_granted('ROLE_ADMIN') or object.getUser() == user", 
 *              "path"="/reservations/{id}"
 *          },
 *          "delete"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "path"="/reservations/{id}"
 *          },
 *          "patch"={
 *              "security"="is_granted('ROLE_ADMIN') or object.getUser() == user", 
 *              "path"="/reservations/{id}"
 *          }
 *      }
 * )
 * @ApiFilter(
 *      DateFilter::class,
 *      properties={"date"}
 * )
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"reservation:read", "order:read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reservations")
     * @Groups({"reservation:read", "order:read"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Service::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"reservation:read", "order:read"})
     */
    private $service;

    /**
     * @ORM\ManyToOne(targetEntity=Table::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"reservation:read", "order:read"})
     */
    private $table_;

    /**
     * @ORM\Column(type="date")
     * @Groups({"reservation:read", "reservation:write", "order:read"})
     */
    private $date;

    /**
     * @Groups("reservation:write")
     */
    private $serviceId;

    /**
     * @Groups("reservation:write")
     */
    private $tableId;

    /**
     * @ORM\OneToOne(targetEntity=Order::class, mappedBy="reservation", cascade={"persist", "remove"})
     */
    private $order_;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): self
    {
        $this->service = $service;

        return $this;
    }

    public function getTable(): ?Table
    {
        return $this->table_;
    }

    public function setTable(?Table $table_): self
    {
        $this->table_ = $table_;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getServiceId(): int
    {
        return $this->serviceId;
    }

    public function setServiceId(int $serviceId): self
    {
        $this->serviceId = $serviceId;

        return $this;
    }

    public function getTableId(): int
    {
        return $this->tableId;
    }

    public function setTableId(int $tableId): self
    {
        $this->tableId = $tableId;

        return $this;
    }

    public function getOrder(): ?Order
    {
        return $this->order_;
    }

    public function setOrder(?Order $order_): self
    {
        // unset the owning side of the relation if necessary
        if ($order_ === null && $this->order_ !== null) {
            $this->order_->setReservation(null);
        }

        // set the owning side of the relation if necessary
        if ($order_ !== null && $order_->getReservation() !== $this) {
            $order_->setReservation($this);
        }

        $this->order_ = $order_;

        return $this;
    }
}
