<?php

declare(strict_types=1);

namespace App\Application\Command\Post\PostCategory;

use App\Domain\Post\Entity\PostCategory;
use App\Domain\Post\Exception\Post\PostCategory\ProcessCreatePostCategoryException;
use App\Domain\Post\Repository\PostCategoryRepositoryContract;
use App\Domain\Shared\Repository\TransactionContract;

class PostCategoryHandler
{
    public function __construct(
        private TransactionContract $transaction,
        private PostCategoryRepositoryContract $postCategoryRepository,
    ) {
    }

    public function handle(PostCategoryCommand $command): void
    {
        $postCategory = PostCategory::createFromCommand($command);

        $this->transaction->startTransaction();
        try {
            $this->postCategoryRepository->save($postCategory);
            $this->transaction->commit();
        } catch (\Throwable $e) {
            $this->transaction->rollback();
            throw new ProcessCreatePostCategoryException(previous: $e);
        }
    }
}
