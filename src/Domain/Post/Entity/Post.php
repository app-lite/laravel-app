<?php

declare(strict_types=1);

namespace App\Domain\Post\Entity;

use App\Application\Command\Post\Post\PostCommand;

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

    public static function createFromCommand(PostCommand $command): self
    {
        return new self(
            $command->id,
            $command->categoryId,
            $command->text,
            $command->description,
            $command->createdAt,
        );
    }
}
