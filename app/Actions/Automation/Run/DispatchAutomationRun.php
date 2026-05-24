<?php

declare(strict_types=1);

namespace App\Actions\Automation\Run;

use App\Enums\Automation\Run\Status;
use App\Jobs\Automation\ProcessAutomationNode;
use App\Models\Automation;
use App\Models\AutomationRun;
use App\Models\AutomationTriggerItem;

class DispatchAutomationRun
{
    public function __invoke(Automation $automation, AutomationTriggerItem $triggerItem): AutomationRun
    {
        $firstNodeId = $this->findFirstRealNodeId($automation);

        $run = AutomationRun::create([
            'automation_id' => $automation->id,
            'trigger_item_id' => $triggerItem->id,
            'status' => Status::Pending,
            'context' => ['trigger' => $triggerItem->payload],
        ]);

        if ($firstNodeId === null) {
            $run->update([
                'status' => Status::Failed,
                'error' => ['message' => __('automations.errors.no_trigger_connection')],
                'finished_at' => now(),
            ]);

            return $run;
        }

        ProcessAutomationNode::dispatch($run, $firstNodeId);

        return $run;
    }

    private function findFirstRealNodeId(Automation $automation): ?string
    {
        $triggerNode = collect($automation->nodes ?? [])->firstWhere('type', 'trigger');

        if ($triggerNode === null) {
            return null;
        }

        $connection = collect($automation->connections ?? [])
            ->firstWhere('source', $triggerNode['id']);

        return $connection['target'] ?? null;
    }
}
