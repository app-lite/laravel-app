<?php

declare(strict_types=1);

namespace App\Domain\Post\Constant;

enum PostEnum
{
    public const DB_TABLE = 'post_posts';

    public const ORDER_FIELD_CREATED_AT = 'created_at';

    public const ORDER_FIELD_LIST = [
        self::ORDER_FIELD_CREATED_AT,
    ];

    public const KEY_ID = 'id';

    public const KEY_LIST = [
        self::KEY_ID,
    ];

    public const STATUS_ACTIVE = 'active';
    public const STATUS_ARCHIVED = 'archived';

    public const STATUS_LIST = [
        self::STATUS_ACTIVE => 'Активен',
        self::STATUS_ARCHIVED => 'Отключён',
    ];
}
