<?php

declare(strict_types=1);

namespace App\Domain\Post\Exception\Post\PostCategory;

class ProcessCreatePostCategoryException extends \Exception
{
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        $message = $message ?: 'Error in create post category process!';
        parent::__construct($message, $code, $previous);
    }
}
