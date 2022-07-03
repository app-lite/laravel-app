<?php

declare(strict_types=1);

namespace App\Domain\Post\Repository;

use App\Domain\Post\Criteria\PostCriteria;
use App\Domain\Post\Entity\Post;

interface PostRepositoryContract
{
    public function getById(string $id): Post;

    public function getByLimitGroupByCategoryId(int $limit): array;

    public function getListByCriteria(PostCriteria $criteria);

    public function count(): int;

    public function getListByIdList(array $idList): array;

    public function getList(): array;

    public function save(Post $post): void;
}
