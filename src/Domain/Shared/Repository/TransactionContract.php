<?php

declare(strict_types=1);

namespace App\Domain\Shared\Repository;

interface TransactionContract
{
    public function startTransaction();

    public function commit();

    public function rollback();
}
