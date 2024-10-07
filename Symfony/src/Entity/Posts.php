<?php

namespace App\Entity;

use App\Repository\PostsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Users;

#[ORM\Entity(repositoryClass: PostsRepository::class)]
class Posts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'title', length: 255)]
    private ?string $title = null;

    #[ORM\Column(name: 'text', type: Types::TEXT, nullable: true)]
    private ?string $text = null;

    #[ORM\Column(name: 'photo', type: Types::JSON, nullable: true)]
    private ?array $photo;

    #[ORM\Column(name: 'author', type: Types::TEXT, nullable: true)]
    private ?string $author = null;

    #[ORM\Column(name: 'created_at', type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
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

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto($photo): static
    {
        $this->photo = $photo;
        return $this;
    }

    public function setAuthor(?string $author): static
    {
        $this->author = $author;
        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeInterface $created_date): static
    {
        $this->createdDate = $created_date;
        return $this;
    }
}