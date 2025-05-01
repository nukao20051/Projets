<?php

namespace App\Entity;

use App\Repository\MedicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MedicationRepository::class)]
class Medication
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(length: 1024, nullable: true)]
    private ?string $text = null;

    #[ORM\Column]
    private ?float $dosage = null;

    #[ORM\Column(length: 5)]
    private ?string $unit = null;

    #[ORM\Column(type: Types::BLOB, nullable: true)]
    private $img;

    #[ORM\OneToMany(targetEntity: Sample::class, mappedBy: 'medication')]
    private Collection $samples;

    #[ORM\Column(nullable: true)]
    private ?bool $pharmacyOnly = null;

    public function __construct()
    {
        $this->samples = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getDosage(): ?float
    {
        return $this->dosage;
    }

    public function setDosage(float $dosage): static
    {
        $this->dosage = $dosage;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(string $unit): static
    {
        $this->unit = $unit;

        return $this;
    }

    public function getImg()
    {
        return $this->img;
    }

    public function setImg($img): static
    {
        $this->img = $img;

        return $this;
    }

    /**
     * @return Collection<int, Sample>
     */
    public function getSamples(): Collection
    {
        return $this->samples;
    }

    public function addMedication(Sample $sample): static
    {
        if (!$this->samples->contains($sample)) {
            $this->samples->add($sample);
            $sample->setMedication($this);
        }

        return $this;
    }

    public function removeMedication(Sample $sample): static
    {
        if ($this->samples->removeElement($sample)) {
            // set the owning side to null (unless already changed)
            if ($sample->getMedication() === $this) {
                $sample->setMedication(null);
            }
        }

        return $this;
    }

    public function getImgBase64(): ?string
    {
        if ($this->img) {
            return 'data:image/jpeg;base64,'.base64_encode(stream_get_contents($this->img));
        }

        return null;
    }

    public function isPharmacyOnly(): ?bool
    {
        return $this->pharmacyOnly;
    }

    public function setPharmacyOnly(bool $pharmacyOnly): static
    {
        $this->pharmacyOnly = $pharmacyOnly;

        return $this;
    }

    public function isPharmcayOnly(): ?bool
    {
        return $this->pharmcayOnly;
    }

    public function setPharmcayOnly(?bool $pharmcayOnly): static
    {
        $this->pharmcayOnly = $pharmcayOnly;

        return $this;
    }
}
