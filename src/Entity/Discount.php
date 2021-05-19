<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\DiscountRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      normalizationContext={
 *          "groups"="discount:read"
 *      },
 *      denormalizationContext={
 *          "groups"="discount:write"
 *      },
 *      collectionOperations={
 *          "get"={
 *              "security"="is_granted('ROLE_USER')", 
 *              "path"="/discounts"
 *          }
 *      },
 *      itemOperations={
 *          "get"={
 *              "security"="is_granted('ROLE_USER')", 
 *              "path"="/discounts/{id}"
 *          }
 *      }
 * )
 * @ORM\Entity(repositoryClass=DiscountRepository::class)
 */
class Discount
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("discount:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("discount:read")
     */
    private $label;

    /**
     * @ORM\Column(type="integer")
     * @Groups("discount:read")
     */
    private $value;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }
}
