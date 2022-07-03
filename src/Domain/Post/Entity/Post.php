<?php

declare(strict_types=1);

namespace App\Domain\Post\Entity;

class Post
{
    public function __construct(
        private string $id,
        private string $categoryId,
        private string $title,
        private ?string $text,
        private \DateTimeImmutable $createdAt,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCategoryId(): string
    {
        return $this->categoryId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
