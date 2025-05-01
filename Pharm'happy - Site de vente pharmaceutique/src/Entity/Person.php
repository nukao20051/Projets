<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: PersonRepository::class)]
class Person implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    public ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    private ?string $lastname = null;

    #[ORM\Column(length: 50)]
    private ?string $firstname = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $birthDat = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $phone = null;

    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'person')]
    private Collection $orders;

    #[ORM\OneToMany(targetEntity: Address::class, mappedBy: 'person')]
    private Collection $address;

    #[ORM\ManyToOne(inversedBy: 'persons')]
    private ?MedicalOffice $medicalOffice = null;

    #[ORM\Column(type: Types::BLOB, nullable: true)]
    private $avatar;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->address = new ArrayCollection();
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials(): void
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getBirthDat(): ?\DateTimeInterface
    {
        return $this->birthDat;
    }

    public function setBirthDat(?\DateTimeInterface $birthDat): static
    {
        $this->birthDat = $birthDat;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setPerson($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            if ($order->getPerson() === $this) {
                $order->setPerson(null);
            }
        }

        return $this;
    }

    public function getAddress(): Collection
    {
        return $this->address;
    }

    public function setAddress(array $addressCollection): static
    {
        foreach ($addressCollection as $address) {
            $this->addAddress($address);
        }

        return $this;
    }

    public function addAddress(Address $address): static
    {
        if (!$this->address->contains($address)) {
            $this->address->add($address);
            $address->setPerson($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): static
    {
        if ($this->address->removeElement($address)) {
            if ($address->getPerson() === $this) {
                $address->setPerson(null);
            }
        }

        return $this;
    }

    public function getMedicalOffice(): ?MedicalOffice
    {
        return $this->medicalOffice;
    }

    public function setMedicalOffice(?MedicalOffice $MedicalOffice): static
    {
        $this->medicalOffice = $MedicalOffice;

        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }
}
