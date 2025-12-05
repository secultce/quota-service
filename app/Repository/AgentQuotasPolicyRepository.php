<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\AgentQuotasPolicy;

class AgentQuotasPolicyRepository
{
    public function all()
    {
        return AgentQuotasPolicy::get();
    }

    public function find(int $id): AgentQuotasPolicy
    {
        return AgentQuotasPolicy::findOrFail($id);
    }

    public function findExisting(int $agentId, int $quotaId)
    {
        return AgentQuotasPolicy::where('agent_id', $agentId)
            ->where('quotas_policy_id', $quotaId)
            ->first();
    }

    public function createOrUpdate(array $attributes, array $data): AgentQuotasPolicy
    {
        return AgentQuotasPolicy::updateOrCreate($attributes, $data);
    }
}
