<?php

declare(strict_types=1);

namespace App\Event;

class QuotaActivityLog
{
    public string $action;
    public ?array $data;
    public int $agentQuotaPolicyId;

    public function __construct(string $action, ?array $data, int $agentQuotaPolicyId)
    {
        $this->action = $action;
        $this->data = $data;
        $this->agentQuotaPolicyId = $agentQuotaPolicyId;
    }
}
