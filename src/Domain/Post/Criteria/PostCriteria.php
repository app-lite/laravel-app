<?php

declare(strict_types=1);

namespace App\Domain\Post\Criteria;

use App\Domain\Post\Constant\PostEnum;
use App\Domain\Shared\Constant\SharedOrderEnum;

class PostCriteria
{
    private ?string $key = null;
    private array $orderList = [];
    private array $categoryIdList = [];

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function addKey(string $key): self
    {
        $this->guardKey($key);
        $this->key = $key;
        return $this;
    }

    public function removeKey(): self
    {
        $this->key = null;
        return $this;
    }

    public function getCategoryIdList(): array
    {
        return $this->categoryIdList;
    }

    public function addCategoryId(string $categoryId): self
    {
        $this->categoryIdList[$categoryId] = $categoryId;
        return $this;
    }

    public function removeCategoryId(string $categoryId): self
    {
        unset($this->categoryIdList[$categoryId]);
        return $this;
    }

    public function addCategoryIdList(array $categoryIdList): self
    {
        foreach ($categoryIdList as $categoryId) {
            $this->categoryIdList[$categoryId] = $categoryId;
        }
        return $this;
    }

    public function removeCategoryIdList(array $categoryIdList): self
    {
        foreach ($categoryIdList as $categoryId) {
            unset($this->categoryIdList[$categoryId]);
        }
        return $this;
    }

    public function guardOrderColumn(string $column)
    {
        if (!in_array($column, PostEnum::ORDER_FIELD_LIST)) {
            throw new \InvalidArgumentException("Incorrect column!");
        }
    }

    /**
     * @param string $column
     * @param string|null $direction
     * @return $this
     */
    public function addOrder(string $column, ?string $direction = 'asc'): self
    {
        $this->guardOrderColumn($column);
        $direction = strtolower($direction);

        $this->guardOrderDirection($direction);

        $this->orderList[$column] = $direction;

        return $this;
    }

    public function removeOrder(string $column): self
    {
        unset($this->orderList[$column]);
        return $this;
    }

    /**
     * @return array
     */
    public function getOrderList(): array
    {
        return $this->orderList;
    }

    public function guardKey(string $key)
    {
        if (!in_array($key, PostEnum::KEY_LIST)) {
            throw new \InvalidArgumentException("Incorrect key!");
        }
    }

    public function guardOrderDirection(string $direction): void
    {
        if (!in_array($direction, SharedOrderEnum::ORDER_DIRECTION_LIST, true)) {
            throw new \InvalidArgumentException('Order direction must be "asc" or "desc".');
        }
    }

    public static function create(): self
    {
        return new self();
    }
}
