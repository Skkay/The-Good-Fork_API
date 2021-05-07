<?php

namespace App\Entity;

use App\Repository\OrderMenuRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderMenuRepository::class)
 */
class OrderMenu
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Menu::class)
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $menu;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="orderedMenu")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $order_;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMenu(): ?Menu
    {
        return $this->menu;
    }

    public function setMenu(?Menu $menu): self
    {
        $this->menu = $menu;

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
