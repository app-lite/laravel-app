<?php

declare(strict_types=1);

namespace App\Domain\Post\Exception\Post\PostCategory;

class PostCategorySaveException extends \Exception
{
    public function __construct(string $message = 'Post category not save!', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
