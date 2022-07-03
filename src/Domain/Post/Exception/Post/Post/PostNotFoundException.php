<?php

declare(strict_types=1);

namespace App\Domain\Post\Exception\Post\Post;

class PostNotFoundException extends \Exception
{
    public function __construct(string $message = 'Post not found!', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
