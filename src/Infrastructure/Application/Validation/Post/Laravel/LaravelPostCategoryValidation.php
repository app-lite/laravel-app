<?php

declare(strict_types=1);

namespace App\Infrastructure\Application\Validation\Post\Laravel;

use App\Application\Validation\Post\PostCategoryValidation;
use App\Domain\Post\Constant\PostCategoryEnum;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule;

class LaravelPostCategoryValidation implements PostCategoryValidation
{
    public function __construct(private Factory $validatorFactory)
    {
    }

    /**
     * @param array $data
     * @return Validator
     */
    public function validate(array $data): mixed
    {
        return $this->validatorFactory->make(
            $data,
            [
                'id' => [
                    'uuid',
                ],
                'title' => [
                    'required',
                    Rule::unique(PostCategoryEnum::DB_TABLE)
                        ->where(function (Builder $query) use ($data) {
                            return $query->where('title', $data['title']);
                        })->ignore($data['id'] ?? null),
                    'max:255',
                ],
            ],
        );
    }
}
