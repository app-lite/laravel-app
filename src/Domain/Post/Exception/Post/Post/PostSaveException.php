<?php

declare(strict_types=1);

namespace App\Domain\Post\Exception\Post\Post;

class PostSaveException extends \Exception
{
    public function __construct(string $message = 'Post not save!', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
