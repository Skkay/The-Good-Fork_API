<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\DrinkRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      normalizationContext={
 *          "groups"="drink:read"
 *      },
 *      denormalizationContext={
 *          "groups"="drink:write"
 *      },
 *      collectionOperations={
 *          "get"={
 *              "security"="is_granted('ROLE_USER')", 
 *              "path"="/drinks"
 *          },
 *          "post"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "path"="/drinks"
 *          }
 *      },
 *      itemOperations={
 *          "get"={
 *              "security"="is_granted('ROLE_USER')", 
 *              "path"="/drinks/{id}"
 *          },
 *          "put"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "path"="/drinks/{id}"
 *          },
 *          "delete"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "path"="/drinks/{id}"
 *          },
 *          "patch"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "path"="/drinks/{id}"
 *          }
 *      }
 * )
 * @ORM\Entity(repositoryClass=DrinkRepository::class)
 */
class Drink
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"drink:read", "menu:read", "order:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"drink:read", "drink:write", "menu:read", "order:read"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"drink:read", "drink:write", "menu:read", "order:read"})
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * @Groups({"drink:read", "drink:write", "menu:read", "order:read"})
     */
    private $price;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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
}
