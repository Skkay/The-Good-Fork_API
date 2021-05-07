<?php

namespace App\Entity;

use App\Repository\OrderDrinkRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderDrinkRepository::class)
 */
class OrderDrink
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Drink::class)
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $drink;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="orderedDrink")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $order_;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDrink(): ?Drink
    {
        return $this->drink;
    }

    public function setDrink(?Drink $drink): self
    {
        $this->drink = $drink;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getOrder(): ?Order
    {
        return $this->order_;
    }

    public function setOrder(?Order $order_): self
    {
        $this->order_ = $order_;

        return $this;
    }
}
