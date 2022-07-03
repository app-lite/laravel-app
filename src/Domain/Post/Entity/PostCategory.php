<?php

declare(strict_types=1);

namespace App\Domain\Post\Entity;

use App\Application\Command\Post\PostCategory\PostCategoryCommand;

class PostCategory
{
    public function __construct(
        private string $id,
        private string $title,
        private ?string $description,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public static function createFromCommand(PostCategoryCommand $command): self
    {
        return new self(
            $command->id,
            $command->title,
            $command->description,
        );
    }
}
