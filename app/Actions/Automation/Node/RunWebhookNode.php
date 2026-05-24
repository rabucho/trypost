<?php

declare(strict_types=1);

namespace App\Actions\Automation\Node;

use App\DataTransferObjects\Automation\NodeRunResult;
use App\Models\AutomationRun;
use App\Services\Automation\ExpressionResolver;
use Illuminate\Support\Facades\Http;

class RunWebhookNode
{
    public function __construct(private ExpressionResolver $resolver) {}

    public function __invoke(AutomationRun $run, array $config): NodeRunResult
    {
        $url = $this->resolver->resolve($config['url'] ?? '', $run->context ?? []);
        $method = strtoupper($config['method'] ?? 'POST');
        $headers = [];

        foreach ($config['headers'] ?? [] as $k => $v) {
            $headers[$k] = $this->resolver->resolve((string) $v, $run->context ?? []);
        }

        $payloadJson = $this->resolver->resolve($config['payload_template'] ?? '{}', $run->context ?? []);
        $payload = json_decode($payloadJson, true) ?? [];

        $response = Http::withHeaders($headers)
            ->send($method, $url, ['json' => $payload]);

        if ($response->serverError()) {
            return NodeRunResult::failed(__('automations.errors.webhook_server_error'), [
                'status' => $response->status(),
                'body' => substr($response->body(), 0, 500),
            ]);
        }

        return NodeRunResult::completed(output: [
            'webhook' => [
                'status' => $response->status(),
                'body' => substr($response->body(), 0, 500),
            ],
        ]);
    }
}
