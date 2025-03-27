<?php

namespace App\Entity;

use App\Repository\SampleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SampleRepository::class)]
class Sample
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $expiration = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'samples')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Medication $medication = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'samples')]
    private ?Order $order = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExpiration(): ?\DateTimeInterface
    {
        return $this->expiration;
    }

    public function setExpiration(\DateTimeInterface $expiration): static
    {
        $this->expiration = $expiration;

        return $this;
    }

    public function getMedication(): ?Medication
    {
        return $this->medication;
    }

    public function setMedication(?Medication $medication): static
    {
        $this->medication = $medication;

        return $this;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): static
    {
        $this->order = $order;

        return $this;
    }
}
