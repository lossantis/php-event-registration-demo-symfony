<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\RegistrationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RegistrationRepository::class)]
#[ORM\Table(name: 'registration')]
#[ORM\UniqueConstraint(name: 'UNIQ_REGISTRATION_EMAIL', columns: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'This email has already been used to register.')]
#[Assert\Expression(
    expression: 'this.getNumberOfVegetarians() <= this.getNumberOfKids()',
    message: 'Number of vegetarians cannot exceed the number of kids.'
)]
class Registration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Name is required.')]
    private string $name;

    #[ORM\Column(name: 'plus_one', type: 'boolean')]
    #[Assert\NotNull(message: 'Please specify if you bring a plus one.')]
    private bool $plusOne = false;

    #[ORM\Column(name: 'number_of_kids', type: 'integer', options: ['unsigned' => true, 'default' => 0])]
    #[Assert\PositiveOrZero(message: 'Number of kids cannot be negative.')]
    private int $numberOfKids = 0;

    #[ORM\Column(name: 'number_of_vegetarians', type: 'integer', options: ['unsigned' => true, 'default' => 0])]
    #[Assert\PositiveOrZero(message: 'Number of vegetarians cannot be negative.')]
    private int $numberOfVegetarians = 0;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Email is required.')]
    #[Assert\Email(message: 'Please enter a valid email address.')]
    private string $email;

    #[ORM\Column(type: 'string', length: 63)]
    #[Assert\NotBlank(message: 'Department is required.')]
    private string $department;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function isPlusOne(): bool
    {
        return $this->plusOne;
    }

    public function setPlusOne(bool $plusOne): self
    {
        $this->plusOne = $plusOne;
        return $this;
    }

    public function getNumberOfKids(): int
    {
        return $this->numberOfKids;
    }

    public function setNumberOfKids(int $numberOfKids): self
    {
        if ($numberOfKids < 0) {
            throw new \InvalidArgumentException('numberOfKids cannot be negative');
        }
        $this->numberOfKids = $numberOfKids;
        return $this;
    }

    public function getNumberOfVegetarians(): int
    {
        return $this->numberOfVegetarians;
    }

    public function setNumberOfVegetarians(int $numberOfVegetarians): self
    {
        if ($numberOfVegetarians < 0) {
            throw new \InvalidArgumentException('numberOfVegetarians cannot be negative');
        }
        $this->numberOfVegetarians = $numberOfVegetarians;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getDepartment(): string
    {
        return $this->department;
    }

    public function setDepartment(string $department): self
    {
        $this->department = $department;
        return $this;
    }
}
