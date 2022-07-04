<?php

declare(strict_types=1);

namespace App\Infrastructure\Domain\Post\Repository\Laravel;

use App\Domain\Post\Criteria\PostCriteria;
use App\Domain\Post\Entity\Post;
use App\Domain\Post\Exception\Post\Post\PostSaveException;
use App\Domain\Post\Repository\PostRepositoryContract;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use samdark\hydrator\Hydrator;

class LaravelPostRepository implements PostRepositoryContract
{
    private const FIELD_LIST = [
        'id',
        'category_id',
        'title',
        'text',
        'created_at',
    ];

    private Hydrator $hydrator;
    private string $table = 'post_posts';

    public function __construct(private ConnectionInterface $db)
    {
        $this->hydrator = new Hydrator([
            'id' => 'id',
            'category_id' => 'categoryId',
            'title' => 'title',
            'text' => 'text',
            'created_at' => 'createdAt',
        ]);
    }

    public function getById(string $id): Post
    {
        $dbResult = $this->db->table($this->table)
            ->select(self::FIELD_LIST)
            ->whereNull('deleted_at')
            ->where('id', $id)
            ->first();

        return $this->hydrateResult($dbResult);
    }

    public function count(): int
    {
        return $this->db->table($this->table)->count();
    }

    public function getListByCriteria(PostCriteria $criteria): array
    {
        $query = $this->db->table($this->table)
            ->select(self::FIELD_LIST);

        $this->prepareQueryByCriteria($query, $criteria);

        return array_map(function (\stdClass $dbResult) {
            return $this->hydrateResult($dbResult);
        }, $query->get()->toArray());
    }

    private function prepareQueryByCriteria(Builder $query, PostCriteria $criteria): Builder
    {
        if (!empty($criteria->getCategoryIdList())) {
            $query->whereIn('category_id', $criteria->getCategoryIdList());
        }

        if (!empty($criteria->getOrderList())) {
            foreach ($criteria->getOrderList() as $column => $direction) {
                $query->orderBy($column, $direction);
            }
        }

        return $query;
    }

    public function getByLimitGroupByCategoryId(int $limit): array
    {
        if (env('DB_CONNECTION') === 'pgsql') {
            $result = $this->getByLimitLateralGroupByCategoryId($limit);
        } else {
            $result = $this->getByLimitWindowFunctionGroupByCategoryId($limit);
        }

        return $result;
    }

    private function getByLimitLateralGroupByCategoryId(int $limit): array
    {
        $lateralSub = $this->db->table($this->table)
            ->select(self::FIELD_LIST)
            ->where('t1.category_id', DB::raw('post_posts.category_id'))
            ->orderBy('created_at', 'desc')
            ->limit($limit)->toSql();

        $dbResult = $this->db->table(
            $this->db->table($this->table)
                ->select('category_id')
                ->groupBy('category_id'),
            't1')
            ->join(DB::raw("LATERAL ({$lateralSub}) as t2"), 't1.category_id', '=', 't2.category_id')
            ->select([
                't2.id',
                't2.category_id',
                't2.title',
                't2.text',
                't2.created_at',
            ])
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();

        $result = [];
        foreach ($dbResult as $item) {
            $result[$item->category_id][$item->id] = $this->hydrateResult($item);
        }

        return $result;
    }

    public function getByLimitWindowFunctionGroupByCategoryId(int $limit): array
    {
        $dbResult = $this->db->table($this->db->table($this->table)
        ->select([
            'id',
            'category_id',
            'title',
            'text',
            'created_at',
            DB::raw('row_number() OVER (
                    PARTITION BY category_id ORDER BY created_at DESC
                ) i'),
        ]), 'posts')
            ->select(self::FIELD_LIST)
        ->where('i', '<=', $limit)
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();

        $result = [];
        foreach ($dbResult as $item) {
            $result[$item->category_id][$item->id] = $this->hydrateResult($item);
        }

        return $result;
    }

    public function getListByIdList(array $idList): array
    {
        $dbResult = $this->db->table($this->table)
            ->select(self::FIELD_LIST)
            ->whereIn('id', $idList)
            ->orderBy('created_at', 'desc')
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
//            ->orderBy('sort')
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();

        return array_map(function (\stdClass $dbResult) {
            return $this->hydrateResult($dbResult);
        }, $dbResult);
    }

    public function hasById(string $id): bool
    {
        return $this->db->table($this->table)->where('id', $id)->exists();
    }

    public function save(Post $post): void
    {
        $prepareData = $this->extract($post);
        if ($this->hasById($prepareData['id'])) {
            $prepareData['updated_at'] = new \DateTimeImmutable();
            $prepareData['deleted_at'] = null;
            try {
                if ($this->db->table($this->table)->where('id', $prepareData['id'])->update($prepareData) === 0) {
                    throw new PostSaveException();
                }
            } catch (\Throwable $e) {
                throw new PostSaveException(previous: $e);
            }
        } else {
            $prepareData['updated_at'] = $prepareData['created_at'];
            try {
                if (!$this->db->table($this->table)->insert($prepareData)) {
                    throw new PostSaveException();
                }
            } catch (\Throwable $e) {
                throw new PostSaveException(previous: $e);
            }
        }
    }

    private function extract(Post $post): array
    {
        return $this->hydrator->extract($post);
    }

    private function hydrateResult(?\stdClass $dbResult): ?Post
    {
        $result = null;
        if ($dbResult) {
            $dbResult = (array)$dbResult;
            $dbResult['created_at'] = $dbResult['created_at'] ? new \DateTimeImmutable($dbResult['created_at']) : null;
            /** @var Post $result */
            $result = $this->hydrator->hydrate($dbResult, Post::class);
        }
        return $result;
    }
}
