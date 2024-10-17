<?php

namespace App\Entity;

use App\Repository\ConferenceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConferenceRepository::class)]
class Conference
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z\s]+$/",
        message: 'Attentions au caractères utilisés.'
    )]
    private $city;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    #[Assert\Range(
        min: 2000,
        max: 2099,
        notInRangeMessage: 'The year must be between {{ min }} and {{ max }}.'
    )]
    #[Assert\Length(
        min: 4,
        max: 4,
        exactMessage: 'The year must be exactly {{ limit }} digits.'
    )]
    private $year;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isInternational;

    // Getters and setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getIsInternational(): ?bool
    {
        return $this->isInternational;
    }

    public function setIsInternational(?bool $isInternational): self
    {
        $this->isInternational = $isInternational;

        return $this;
    }
}
