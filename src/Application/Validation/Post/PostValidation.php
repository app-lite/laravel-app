<?php

declare(strict_types=1);

namespace App\Application\Validation\Post;

interface PostValidation
{
    public function validate(array $data): mixed;
}
