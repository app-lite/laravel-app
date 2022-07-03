<?php

declare(strict_types=1);

namespace App\Application\Command\Post\Post;

use App\Domain\Post\Entity\Post;
use App\Domain\Post\Exception\Post\Post\ProcessCreatePostException;
use App\Domain\Post\Repository\PostRepositoryContract;
use App\Domain\Shared\Repository\TransactionContract;

class PostHandler
{
    public function __construct(
        private TransactionContract $transaction,
        private PostRepositoryContract $postRepository,
    ) {
    }

    public function handle(PostCommand $command): void
    {
        $post = Post::createFromCommand($command);

        $this->transaction->startTransaction();
        try {
            $this->postRepository->save($post);
            $this->transaction->commit();
        } catch (\Throwable $e) {
            $this->transaction->rollback();
            throw new ProcessCreatePostException(previous: $e);
        }
    }
}
