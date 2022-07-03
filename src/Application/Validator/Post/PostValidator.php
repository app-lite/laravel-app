<?php

declare(strict_types=1);

namespace App\Application\Validator\Post;

interface PostValidator
{
    public function validate(array $data): mixed;
}
