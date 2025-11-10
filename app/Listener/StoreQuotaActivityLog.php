<?php

declare(strict_types=1);

namespace App\Listener;

use App\Model\QuotaActivityLog;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Event\Contract\ListenerInterface;

class StoreQuotaActivityLog implements ListenerInterface
{
    const ACTIVITY = [
        'create' => 'criação',
        'update' => 'atualização',
        'delete' => 'remoção',
    ];

    public function __construct(
        private StdoutLoggerInterface $logger
    ) {}

    public function listen(): array
    {
        return [
            \App\Event\QuotaActivityLog::class,
        ];
    }

    public function process(object $event): void
    {
        QuotaActivityLog::create([
            'action' => $event->action,
            'data' => $event->data,
            'agent_quota_policy_id' => $event->agentQuotaPolicyId,
        ]);

        $this->logger->info("Uma atividade de " . self::ACTIVITY[$event->action] . " de cota foi registrada. ID da atividade: {$event->agentQuotaPolicyId}.");
    }
}
