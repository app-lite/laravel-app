<?php

declare(strict_types=1);

namespace App\Infrastructure\Application\Validation\Post\Laravel;

use App\Application\Validation\Post\PostValidation;
use App\Domain\Post\Constant\PostCategoryEnum;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class LaravelPostValidation implements PostValidation
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
                'category_id' => [
                    'uuid',
                    'required',
                    Rule::exists(PostCategoryEnum::DB_TABLE, 'id'),
                ],
                'title' => [
                    'required',
                    'max:255',
                ],
                'text' => [
                    'string',
                    'required',
                ],
            ],
        );
    }
}
