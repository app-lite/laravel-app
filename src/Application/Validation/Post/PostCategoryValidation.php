<?php

declare(strict_types=1);

namespace App\Application\Validation\Post;

interface PostCategoryValidation
{
    public function validate(array $data): mixed;
}
