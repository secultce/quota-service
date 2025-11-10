<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\QuotasPolicy;

class QuotasPolicyRepository
{
    public function find(int $id): QuotasPolicy
    {
        return QuotasPolicy::findOrFail($id);
    }
}
