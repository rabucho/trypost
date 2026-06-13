<?php

declare(strict_types=1);

namespace App\Actions\Automation\Node;

use App\DataTransferObjects\Automation\NodeRunResult;
use App\Models\AutomationRun;
use InvalidArgumentException;

class RunDelayNode
{
    public function __invoke(AutomationRun $run, array $config): NodeRunResult
    {
        $duration = (int) ($config['duration'] ?? 0);
        $unit = $config['unit'] ?? 'minutes';

        $until = match ($unit) {
            'minutes' => now()->addMinutes($duration),
            'hours' => now()->addHours($duration),
            'days' => now()->addDays($duration),
            default => throw new InvalidArgumentException("Unknown delay unit: {$unit}"),
        };

        return NodeRunResult::sleep($until);
    }
}
