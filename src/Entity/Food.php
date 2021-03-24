<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FoodRepository;
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
 *              "path"="/foods"
 *          },
 *          "post"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "path"="/foods"
 *          }
 *      },
 *      itemOperations={
 *          "get"={
 *              "security"="is_granted('ROLE_USER')", 
 *              "path"="/foods/{id}"
 *          },
 *          "put"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "path"="/foods/{id}"
 *          },
 *          "delete"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "path"="/foods/{id}"
 *          },
 *          "patch"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "path"="/foods/{id}"
 *          }
 *      }
 * )
 * @ORM\Entity(repositoryClass=FoodRepository::class)
 */
class Food
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
