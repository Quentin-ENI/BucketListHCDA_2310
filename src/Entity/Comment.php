<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 4,
        max: 255,
        minMessage: 'Your comment must be at least {{ limit }} characters long',
        maxMessage: 'Your comment cannot be longer than {{ limit }} characters',
    )]
    private ?string $content = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Assert\Range(
        min: 1,
        max: 5,
        notInRangeMessage: 'Grade must be between {{ min }} and {{ max }}',
    )]
    private ?int $grade = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne]
    private ?User $author = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    private ?Wish $wish = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getGrade(): ?int
    {
        return $this->grade;
    }

    public function setGrade(int $grade): static
    {
        $this->grade = $grade;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getWish(): ?Wish
    {
        return $this->wish;
    }

    public function setWish(?Wish $wish): static
    {
        $this->wish = $wish;

        return $this;
    }
}
