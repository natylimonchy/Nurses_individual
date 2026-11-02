<?php

namespace App\Entity;

use App\Repository\NurseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: NurseRepository::class)]
class Nurse
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: 'El ID interno no puede estar vacío')]
    #[Assert\Length(max: 20, maxMessage: 'El ID interno no puede tener más de 20 caracteres')]
    #[ORM\Column(length: 20)]
    private ?string $internalId = null;

    #[Assert\NotBlank(message: 'El nombre no puede estar vacío')]
    #[Assert\Length(max: 50, maxMessage: 'El nombre no puede tener más de 50 caracteres')]
    #[Assert\Regex(pattern: '/^[\p{L} ]+$/u', message: 'El nombre solo puede contener letras y espacios')]
    #[ORM\Column(length: 50)]
    private ?string $firstName = null;

    #[Assert\NotBlank(message: 'El apellido no puede estar vacío')]
    #[Assert\Length(max: 50, maxMessage: 'El apellido no puede tener más de 50 caracteres')]
    #[Assert\Regex(pattern: '/^[\p{L} ]+$/u', message: 'El apellido solo puede contener letras y espacios')]
    #[ORM\Column(length: 50)]
    private ?string $lastName = null;

    #[Assert\NotBlank(message: 'El número de licencia no puede estar vacío')]
    #[Assert\Length(max: 20, maxMessage: 'El número de licencia no puede tener más de 20 caracteres')]
    #[Assert\Regex(pattern: '/^[A-Za-z0-9]+$/', message: 'El número de licencia solo puede contener letras y números')]
    #[ORM\Column(length: 20)]
    private ?string $licenseNumber = null;

    #[Assert\NotBlank(message: 'El usuario no puede estar vacío')]
    #[Assert\Length(max: 50, maxMessage: 'El usuario no puede tener más de 50 caracteres')]
    #[Assert\Regex(pattern: '/^\S+$/', message: 'El usuario no puede contener espacios')]
    #[ORM\Column(length: 50)]
    private ?string $username = null;

    #[Assert\NotBlank(message: 'La contraseña no puede estar vacía')]
    #[Assert\Length(min: 8, max: 255, minMessage: 'La contraseña debe tener al menos 8 caracteres', maxMessage: 'La contraseña no puede tener más de 255 caracteres')]
    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[Assert\NotBlank(message: 'El email no puede estar vacío')]
    #[Assert\Email(message: 'El email no tiene un formato válido')]
    #[Assert\Length(max: 100, maxMessage: 'El email no puede tener más de 100 caracteres')]
    #[ORM\Column(length: 100)]
    private ?string $email = null;

    #[Assert\NotBlank(message: 'El turno no puede estar vacío')]
    #[Assert\Choice(choices: ['M', 'T', 'N'], message: 'El turno debe ser M, T o N')]
    #[ORM\Column(length: 20)]
    private ?string $shift = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInternalId(): ?string
    {
        return $this->internalId;
    }

    public function setInternalId(string $internalId): static
    {
        $this->internalId = $internalId;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getLicenseNumber(): ?string
    {
        return $this->licenseNumber;
    }

    public function setLicenseNumber(string $licenseNumber): static
    {
        $this->licenseNumber = $licenseNumber;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

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

    public function getShift(): ?string
    {
        return $this->shift;
    }

    public function setShift(string $shift): static
    {
        $this->shift = $shift;

        return $this;
    }

    
}
