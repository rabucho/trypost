<script setup lang="ts">
import { IconSend } from '@tabler/icons-vue';
import { Handle, Position } from '@vue-flow/core';
import { computed } from 'vue';

import { PublishMode } from '@/types/automation/publish-mode';

const props = defineProps<{
    data: {
        mode?: string;
        scheduled_offset?: number;
    };
    selected?: boolean;
}>();

const summary = computed(() => {
    const mode = props.data.mode ?? PublishMode.Now;
    if (mode === PublishMode.Scheduled && props.data.scheduled_offset != null) {
        return `scheduled · +${props.data.scheduled_offset} min`;
    }
    return mode;
});
</script>

<template>
    <div
        class="automation-node automation-node--accent-emerald"
        :class="{ 'is-selected': selected }"
    >
        <div class="automation-node__header">
            <div class="automation-node__icon-tile automation-node__icon-tile--emerald">
                <IconSend :size="16" />
            </div>
            <span class="automation-node__title">{{ $t('automations.nodes.publish') }}</span>
        </div>
        <div class="automation-node__summary">
            {{ summary }}
        </div>
        <Handle
            type="target"
            :position="Position.Left"
            class="!bg-emerald-500"
        />
        <Handle
            type="source"
            :position="Position.Right"
            class="!bg-emerald-500"
        />
    </div>
</template>
