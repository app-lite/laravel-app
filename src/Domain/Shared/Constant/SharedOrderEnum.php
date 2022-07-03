<?php

declare(strict_types=1);

namespace App\Domain\Shared\Constant;

enum SharedOrderEnum
{
    public const ORDER_DIRECTION_ASC = 'asc';
    public const ORDER_DIRECTION_DESC = 'desc';

    public const ORDER_DIRECTION_LIST = [
        self::ORDER_DIRECTION_ASC,
        self::ORDER_DIRECTION_DESC,
    ];
}
