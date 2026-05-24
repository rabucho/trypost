<script setup lang="ts">
import { Handle, Position } from '@vue-flow/core';
import { IconWorld } from '@tabler/icons-vue';
import { computed } from 'vue';

const props = defineProps<{
    data: {
        url?: string;
        method?: string;
    };
    selected?: boolean;
}>();

const summary = computed(() => {
    const method = (props.data.method ?? 'GET').toUpperCase();
    const url = props.data.url;
    if (!url) return method;
    let host = url;
    try {
        host = new URL(url).hostname;
    } catch {
        // not a valid URL, fall back to raw string
    }
    return `${method} · ${host}`;
});
</script>

<template>
    <div
        class="automation-node automation-node--accent-slate"
        :class="{ 'is-selected': selected }"
    >
        <div class="automation-node__header">
            <div class="automation-node__icon-tile automation-node__icon-tile--slate">
                <IconWorld :size="16" />
            </div>
            <span class="automation-node__title">{{ $t('automations.nodes.http_request') }}</span>
        </div>
        <div class="automation-node__summary">
            {{ summary }}
        </div>
        <Handle type="target" :position="Position.Left" class="!bg-slate-500" />
        <Handle type="source" :position="Position.Right" class="!bg-slate-500" />
    </div>
</template>
