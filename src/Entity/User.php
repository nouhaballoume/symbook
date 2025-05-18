<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private bool $isVerified = false;


    #[ORM\Column(length: 50)]
    #[Assert\Regex(
    pattern: '/^[a-zA-ZÀ-ÿ\s\-]+$/',
    message: "Le nom ne doit contenir que des lettres, espaces et tirets."
)]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    #[Assert\Regex(
    pattern: '/^[a-zA-ZÀ-ÿ\s\-]+$/',
    message: "Le prénom ne doit contenir que des lettres, espaces et tirets."
)]
    private ?string $prenom = null;

    #[ORM\Column(type: 'date', nullable: true)]
    #[Assert\LessThanOrEqual(
    value: "today",
    message: "La date de naissance doit être antérieure à aujourd'hui."
)]
    private ?\DateTimeInterface $dateNaissance = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Assert\Regex(
    pattern: '/^[0-9]+$/',
    message: "Le numéro de téléphone ne doit contenir que des chiffres."
)]
    private ?string $numeroTelephone = null;



    public function getNom(): ?string
{
    return $this->nom;
}

public function setNom(string $nom): static
{
    $this->nom = $nom;

    return $this;
}

public function getPrenom(): ?string
{
    return $this->prenom;
}

public function setPrenom(string $prenom): static
{
    $this->prenom = $prenom;

    return $this;
}

public function getDateNaissance(): ?\DateTimeInterface
{
    return $this->dateNaissance;
}

public function setDateNaissance(?\DateTimeInterface $dateNaissance): static
{
    $this->dateNaissance = $dateNaissance;

    return $this;
}

public function getNumeroTelephone(): ?string
{
    return $this->numeroTelephone;
}

public function setNumeroTelephone(?string $numeroTelephone): static
{
    $this->numeroTelephone = $numeroTelephone;

    return $this;
}









    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }
}
