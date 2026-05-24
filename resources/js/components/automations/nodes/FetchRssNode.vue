<script setup lang="ts">
import { Handle, Position } from '@vue-flow/core';
import { IconRss } from '@tabler/icons-vue';
import { computed } from 'vue';

const props = defineProps<{
    data: {
        feed_url?: string;
    };
    selected?: boolean;
}>();

const summary = computed(() => {
    const url = props.data.feed_url;
    if (!url) return '—';
    try {
        return new URL(url).hostname;
    } catch {
        return url;
    }
});
</script>

<template>
    <div
        class="automation-node automation-node--accent-amber"
        :class="{ 'is-selected': selected }"
    >
        <div class="automation-node__header">
            <div class="automation-node__icon-tile automation-node__icon-tile--amber">
                <IconRss :size="16" />
            </div>
            <span class="automation-node__title">{{ $t('automations.nodes.fetch_rss') }}</span>
        </div>
        <div class="automation-node__summary">
            {{ summary }}
        </div>
        <Handle type="target" :position="Position.Left" class="!bg-amber-500" />
        <Handle type="source" :position="Position.Right" class="!bg-amber-500" />
    </div>
</template>
