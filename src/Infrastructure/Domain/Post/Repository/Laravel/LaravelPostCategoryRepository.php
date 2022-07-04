<?php

declare(strict_types=1);

namespace App\Infrastructure\Domain\Post\Repository\Laravel;

use App\Domain\Post\Constant\PostCategoryEnum;
use App\Domain\Post\Entity\PostCategory;
use App\Domain\Post\Exception\Post\PostCategory\PostCategorySaveException;
use App\Domain\Post\Repository\PostCategoryRepositoryContract;
use Illuminate\Database\ConnectionInterface;
use samdark\hydrator\Hydrator;

class LaravelPostCategoryRepository implements PostCategoryRepositoryContract
{
    private const FIELD_LIST = [
        'id',
        'title',
        'description',
    ];

    private Hydrator $hydrator;
    private string $table = PostCategoryEnum::DB_TABLE;

    public function __construct(private ConnectionInterface $db)
    {
        $this->hydrator = new Hydrator([
            'id' => 'id',
            'title' => 'title',
            'description' => 'description',
        ]);
    }

    public function findById(string $id): ?PostCategory
    {
        $dbResult = $this->db->table($this->table)
            ->select(self::FIELD_LIST)
            ->whereNull('deleted_at')
            ->where('id', $id)
            ->first();

        return $dbResult ? $this->hydrateResult($dbResult) : null;
    }

    public function getById(string $id): PostCategory
    {
        $dbResult = $this->db->table($this->table)
            ->select(self::FIELD_LIST)
            ->whereNull('deleted_at')
            ->where('id', $id)
            ->first();

        return $this->hydrateResult($dbResult);
    }

    public function getListByIdList(array $idList): array
    {
        $dbResult = $this->db->table($this->table)
            ->select(self::FIELD_LIST)
            ->whereIn('id', $idList)
            ->orderBy('title')
            ->get()
            ->toArray();

        return array_map(function (\stdClass $dbResult) {
            return $this->hydrateResult($dbResult);
        }, $dbResult);
    }

    public function getList(): array
    {
        $dbResult = $this->db->table($this->table)
            ->select(self::FIELD_LIST)
            ->orderBy('title')
            ->get()
            ->toArray();

        return array_map(function (\stdClass $dbResult) {
            return $this->hydrateResult($dbResult);
        }, array_column($dbResult, null, 'id'));
    }

    public function hasById(string $id): bool
    {
        return $this->db->table($this->table)->where('id', $id)->exists();
    }

    public function save(PostCategory $postCategory): void
    {
        $prepareData = $this->extract($postCategory);
        if ($this->hasById($prepareData['id'])) {
            $prepareData['updated_at'] = new \DateTimeImmutable();
            $prepareData['deleted_at'] = null;
            try {
                if ($this->db->table($this->table)->where('id', $prepareData['id'])->update($prepareData) === 0) {
                    throw new PostCategorySaveException();
                }
            } catch (\Throwable $e) {
                throw new PostCategorySaveException(previous: $e);
            }
        } else {
            $createAndUpdateDate = new \DateTimeImmutable();
            $prepareData['created_at'] = $createAndUpdateDate;
            $prepareData['updated_at'] = $createAndUpdateDate;
            try {
                if (!$this->db->table($this->table)->insert($prepareData)) {
                    throw new PostCategorySaveException();
                }
            } catch (\Throwable $e) {
                throw new PostCategorySaveException(previous: $e);
            }
        }
    }

    private function extract(PostCategory $postCategory): array
    {
        return $this->hydrator->extract($postCategory);
    }

    private function hydrateResult(?\stdClass $dbResult): ?PostCategory
    {
        $result = null;
        if ($dbResult) {
            $dbResult = (array)$dbResult;
            /** @var PostCategory $result */
            $result = $this->hydrator->hydrate($dbResult, PostCategory::class);
        }
        return $result;
    }
}
