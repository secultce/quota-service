<?php

declare(strict_types=1);

namespace App\Service;

use App\Event\QuotaActivityLog;
use App\Repository\AgentQuotasPolicyRepository;
use App\Repository\QuotasPolicyRepository;
use Carbon\Carbon;
use Psr\EventDispatcher\EventDispatcherInterface;

class AgentQuotasPolicyService
{
    public function __construct(
        private AgentQuotasPolicyRepository $agentQuotasRepo,
        private EventDispatcherInterface $eventDispatcher,
        private QuotasPolicyRepository $quotaRepo,
    ) {}

    public function listAll()
    {
        return $this->agentQuotasRepo->all();
    }

    public function getById(int $id)
    {
        return $this->agentQuotasRepo->find($id);
    }

    public function store(array $data)
    {
        $existing = $this->agentQuotasRepo->findExisting(
            (int)$data['agent_id'],
            (int)$data['quotas_policy_id']
        );

        $attributes = [
            'agent_id' => $data['agent_id'],
            'quotas_policy_id' => $data['quotas_policy_id'],
        ];

        $quota = $this->quotaRepo->find((int)$data['quotas_policy_id']);
        $data['end_date'] = (new Carbon($data['start_date']))->addYears($quota->validity_duration);
        $agentQuota = $this->agentQuotasRepo->createOrUpdate($attributes, $data);

        $this->eventDispatcher->dispatch(
            new QuotaActivityLog(
                action: $existing ? 'update' : 'create',
                data: $agentQuota->toArray(),
                agentQuotaPolicyId: $agentQuota->id,
            )
        );

        return $agentQuota;
    }

    public function delete(int $id, array $data): void
    {
        $agentQuota = $this->agentQuotasRepo->find($id);
        $agentQuota->delete();

        $agentQuota = $agentQuota->fresh()->toArray();
        $agentQuota['deleted_by'] = $data['deleted_by'];

        $this->eventDispatcher->dispatch(
            new QuotaActivityLog(
                action: 'delete',
                data: $agentQuota,
                agentQuotaPolicyId: $id,
            )
        );
    }
}
