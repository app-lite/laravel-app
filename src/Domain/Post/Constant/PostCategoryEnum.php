<?php

declare(strict_types=1);

namespace App\Domain\Post\Constant;

enum PostCategoryEnum
{
    public const ID_NAME_FIELD = 'Id';
    public const TITLE_NAME_FIELD = 'Title';

    public const DB_TABLE = 'post_categories';

    public const ORDER_FIELD_SORT = 'sort';

    public const ORDER_FIELD_LIST = [
        self::ORDER_FIELD_SORT,
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
