<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Automation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AutomationResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'workspace_id' => $this->workspace_id,
            'name' => $this->name,
            'status' => $this->status->value,
            'nodes' => $this->maskSensitiveNodeFields($this->nodes ?? []),
            'connections' => $this->connections ?? [],
            'activated_at' => $this->activated_at,
            'paused_at' => $this->paused_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * Replace any stored credentials with a placeholder before they leave
     * the server. The frontend treats the placeholder as "keep current" on
     * save (see Automation::booted()), so editing other fields doesn't wipe
     * the stored secret.
     *
     * @param  array<int, array<string, mixed>>  $nodes
     * @return array<int, array<string, mixed>>
     */
    private function maskSensitiveNodeFields(array $nodes): array
    {
        foreach ($nodes as &$node) {
            foreach (Automation::SENSITIVE_NODE_FIELDS as $field) {
                if (data_get($node, "data.{$field}") !== null && data_get($node, "data.{$field}") !== '') {
                    data_set($node, "data.{$field}", Automation::SENSITIVE_PLACEHOLDER);
                }
            }
        }

        return $nodes;
    }
}
