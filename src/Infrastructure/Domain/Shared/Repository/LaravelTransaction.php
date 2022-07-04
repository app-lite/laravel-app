<?php

declare(strict_types=1);

namespace App\Infrastructure\Domain\Shared\Repository;

use App\Domain\Shared\Repository\TransactionContract;
use Illuminate\Database\ConnectionInterface;

class LaravelTransaction implements TransactionContract
{
    public function __construct(private ConnectionInterface $db)
    {
    }

    public function startTransaction()
    {
        $this->db->beginTransaction();
    }

    public function commit()
    {
        $this->db->commit();
    }

    public function rollback()
    {
        $this->db->rollBack();
    }
}
