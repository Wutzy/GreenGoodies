<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['product:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(length: 255)]
    private ?string $shortDescription = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $imageFilename = null;

    #[Groups(['product:read'])]
    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups(['product:read'])]
    public function getName(): ?string
    {
        return $this->name;
    }

    #[Groups(['product:read'])]
    public function getPrice(): ?float
    {
        return $this->price;
    }

    #[Groups(['product:read'])]
    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    #[Groups(['product:read'])]
    public function getDescription(): ?string
    {
        return $this->description;
    }

    #[Groups(['product:read'])]
    public function getImageFilename(): ?string
    {
        return $this->imageFilename;
    }

    // setters inchangés
    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;
        return $this;
    }

    public function setShortDescription(string $shortDescription): static
    {
        $this->shortDescription = $shortDescription;
        return $this;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function setImageFilename(string $imageFilename): static
    {
        $this->imageFilename = $imageFilename;
        return $this;
    }
}
