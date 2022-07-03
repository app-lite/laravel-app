<?php

declare(strict_types=1);

namespace App\Application\Query\Post;

use App\Domain\Post\Criteria\PostCategoryCriteria;
use App\Domain\Post\Entity\PostCategory;
use App\Domain\Post\Repository\PostCategoryRepositoryContract;

class PostCategoryFetcher
{
    public function __construct(private PostCategoryRepositoryContract $postCategoryRepository)
    {
    }

    public function findById(string $id): ?PostCategory
    {
        return $this->postCategoryRepository->findById($id);
    }

    public function getById(string $id): PostCategory
    {
        return $this->postCategoryRepository->getById($id);
    }

    /**
     * @return PostCategory[]
     */
    public function getListByIdList(array $idList): array
    {
        $criteria = PostCategoryCriteria::create();
        return $this->postCategoryRepository->getListByIdList($idList);
    }

    /**
     * @return PostCategory[]
     */
    public function getList(): array
    {
        return $this->postCategoryRepository->getList();
    }
}
