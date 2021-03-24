<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\DrinkRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      normalizationContext={
 *          "groups"={"read"}
 *      },
 *      denormalizationContext={
 *          "groups"={"write"}
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
     * @Groups("read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"read", "write"})
     */
    private $name;

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
}
