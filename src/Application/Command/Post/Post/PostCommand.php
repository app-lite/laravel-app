<?php

declare(strict_types=1);

namespace App\Application\Command\Post\Post;

use Ramsey\Uuid\Uuid;

class PostCommand
{
    public function __construct(
        public readonly string $id,
        public readonly string $categoryId,
        public readonly string $text,
        public readonly ?string $description,
        public readonly \DateTimeImmutable $createdAt,
    ) {
    }

    public static function createFromData(array $data): self
    {
        return new self(
            $data['id'] ?? Uuid::uuid4()->toString(),
            $data['category_id'],
            $data['title'],
            $data['text'],
            $data['created_at'] ?? new \DateTimeImmutable(),
        );
    }
}
