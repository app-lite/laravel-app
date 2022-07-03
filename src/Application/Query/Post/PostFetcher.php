<?php

declare(strict_types=1);

namespace App\Application\Query\Post;

use App\Domain\Post\Constant\PostEnum;
use App\Domain\Post\Criteria\PostCriteria;
use App\Domain\Post\Entity\Post;
use App\Domain\Post\Repository\PostRepositoryContract;
use App\Domain\Shared\Constant\SharedOrderEnum;

class PostFetcher
{
    public function __construct(private PostRepositoryContract $postRepository)
    {
    }

    public function getById(string $id): Post
    {
        return $this->postRepository->getById($id);
    }

    public function getByLimitGroupByCategoryId(int $limit): array
    {
        return $this->postRepository->getByLimitGroupByCategoryId($limit);
    }

    public function count(): int
    {
        return $this->postRepository->count();
    }

    /**
     * @return Post[]
     */
    public function getListByIdList(array $idList): array
    {
        return $this->postRepository->getListByIdList($idList);
    }

    /**
     * @return Post[]
     */
    public function getList(): array
    {
        return $this->postRepository->getList();
    }

    /**
     * @return Post[]
     */
    public function getListByCategoryId(string $categoryId): array
    {
        $criteria = PostCriteria::create();
        $criteria->addCategoryId($categoryId);
        $criteria->addOrder(PostEnum::ORDER_FIELD_CREATED_AT, SharedOrderEnum::ORDER_DIRECTION_DESC);
        return $this->postRepository->getListByCriteria($criteria);
    }
}
