<?php

declare(strict_types=1);

namespace App\Application\Validator\Post;

interface PostCategoryValidator
{
    public function validate(array $data): mixed;
}
