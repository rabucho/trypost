<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { IconArrowLeft, IconCircleCheck, IconCircleDot, IconCircleX, IconPlayerPause, IconPlayerPlay, IconTrash } from '@tabler/icons-vue';
import { trans } from 'laravel-vue-i18n';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';

import ConfirmDeleteModal from '@/components/ConfirmDeleteModal.vue';
import JsonViewer from '@/components/JsonViewer.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import dayjs from '@/dayjs';
import AppLayout from '@/layouts/AppLayout.vue';
import {
    activate as activateAutomation,
    destroy as destroyAutomation,
    edit as editAutomation,
    index as automationsIndex,
    pause as pauseAutomation,
} from '@/routes/app/automations';
import { retryRun as retryRunRoute } from '@/actions/App/Http/Controllers/App/AutomationController';
import type { Automation } from '@/types/automation/automation';
import type { Run } from '@/types/automation/run';
import type { TriggerItem } from '@/types/automation/trigger-item';

const props = defineProps<{
    automation: Automation;
    runs: Run[];
    triggerItems: TriggerItem[];
}>();

const retry = (run: Run) => {
    router.post(retryRunRoute.url({ automation: props.automation.id, run: run.id }));
};

const statusConfig = (status: string) => {
    const configs: Record<string, { icon: typeof IconCircleDot; label: string; variant: 'default' | 'secondary' | 'destructive' | 'outline' }> = {
        draft: { icon: IconCircleDot, label: trans('automations.status.draft'), variant: 'outline' },
        active: { icon: IconCircleCheck, label: trans('automations.status.active'), variant: 'default' },
        paused: { icon: IconCircleX, label: trans('automations.status.paused'), variant: 'secondary' },
    };
    return configs[status] ?? configs['draft'];
};

const formatDateTime = (date: string | null) => {
    if (!date) return '—';
    return dayjs.utc(date).local().format('D MMM YYYY, HH:mm');
};

const runStatusVariant = (status: string): 'default' | 'secondary' | 'destructive' | 'outline' => {
    if (status === 'completed') return 'default';
    if (status === 'failed') return 'destructive';
    if (status === 'running') return 'secondary';
    return 'outline';
};

const activeTab = ref('overview');

const deleteModal = ref<InstanceType<typeof ConfirmDeleteModal> | null>(null);

const openDeleteModal = () => {
    deleteModal.value?.open({
        url: destroyAutomation.url(props.automation.id),
        confirmText: props.automation.name,
    });
};

const isActive = computed(() => props.automation.status === 'active');
const isToggling = ref(false);

const toggleActive = () => {
    if (isToggling.value) return;
    isToggling.value = true;
    const url = isActive.value
        ? pauseAutomation.url(props.automation.id)
        : activateAutomation.url(props.automation.id);
    router.post(url, {}, {
        preserveScroll: true,
        onFinish: () => { isToggling.value = false; },
        onError: (errors: Record<string, string>) => {
            const fallback = isActive.value
                ? trans('automations.form.pause_error_fallback')
                : trans('automations.form.activate_error_fallback');
            const msg = (errors as any).message ?? fallback;
            toast.error(msg);
        },
    });
};
</script>

<template>
    <Head :title="automation.name" />

    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-6 px-6 py-8">
            <div class="flex items-start justify-between gap-4">
                <div class="flex min-w-0 items-center gap-3">
                    <Link :href="automationsIndex.url()">
                        <Button variant="outline" size="icon-sm">
                            <IconArrowLeft class="size-4" />
                        </Button>
                    </Link>
                    <div class="min-w-0">
                        <div class="flex items-center gap-3">
                            <h1 class="truncate text-4xl font-semibold" style="font-family: var(--font-display)">
                                {{ automation.name }}
                            </h1>
                            <Badge :variant="statusConfig(automation.status).variant" class="shrink-0">
                                <component :is="statusConfig(automation.status).icon" class="size-3" />
                                {{ statusConfig(automation.status).label }}
                            </Badge>
                        </div>
                        <p v-if="automation.activated_at" class="mt-1 text-sm text-foreground/60">
                            {{ $t('automations.show.activated') }} {{ formatDateTime(automation.activated_at) }}
                        </p>
                    </div>
                </div>
                <div class="flex shrink-0 items-center gap-2">
                    <Link :href="editAutomation.url(automation.id)">
                        <Button variant="outline">{{ $t('automations.actions.edit') }}</Button>
                    </Link>
                    <Button @click="toggleActive" :disabled="isToggling">
                        <IconPlayerPause v-if="isActive" class="size-4" />
                        <IconPlayerPlay v-else class="size-4" />
                        {{ isActive ? $t('automations.actions.pause') : $t('automations.actions.activate') }}
                    </Button>
                    <Button
                        variant="outline"
                        size="icon"
                        class="bg-rose-100 hover:bg-rose-200"
                        :aria-label="$t('automations.actions.delete')"
                        @click="openDeleteModal"
                    >
                        <IconTrash class="size-4 text-rose-700" />
                    </Button>
                </div>
            </div>

            <Tabs v-model="activeTab">
                <TabsList>
                    <TabsTrigger value="overview">{{ $t('automations.show.tabs.overview') }}</TabsTrigger>
                    <TabsTrigger value="runs">{{ $t('automations.show.tabs.runs') }} ({{ runs.length }})</TabsTrigger>
                    <TabsTrigger value="items">{{ $t('automations.show.tabs.trigger_items') }} ({{ triggerItems.length }})</TabsTrigger>
                </TabsList>

                <TabsContent value="overview" class="mt-4">
                    <div class="flex items-center justify-center rounded-xl border-2 border-dashed border-foreground/25 bg-card p-12 text-center">
                        <p class="text-foreground/60">{{ $t('automations.show.canvas_placeholder') }}</p>
                    </div>
                </TabsContent>

                <TabsContent value="runs" class="mt-4">
                    <div v-if="runs.length === 0" class="rounded-xl border-2 border-dashed border-foreground/25 bg-card p-8 text-center">
                        <p class="text-foreground/60">{{ $t('automations.show.empty_runs') }}</p>
                    </div>
                    <ul v-else class="divide-y rounded-xl border-2 border-foreground/10 bg-card">
                        <li v-for="run in runs" :key="run.id" class="flex items-start justify-between p-4 gap-4">
                            <div class="space-y-1 min-w-0">
                                <p class="font-mono text-xs text-foreground/50 truncate">{{ run.id }}</p>
                                <div class="flex items-center gap-2">
                                    <Badge :variant="runStatusVariant(run.status)">{{ run.status }}</Badge>
                                    <span class="text-sm text-foreground/60">{{ $t('automations.show.started') }} {{ formatDateTime(run.started_at) }}</span>
                                </div>
                                <p v-if="run.error" class="text-sm text-destructive">{{ run.error.message }}</p>
                            </div>
                            <div class="flex shrink-0 items-center gap-3">
                                <Button v-if="run.status === 'failed'" variant="ghost" size="sm" @click="retry(run)">{{ $t('automations.actions.retry') }}</Button>
                                <span class="text-sm text-foreground/60">{{ formatDateTime(run.finished_at) }}</span>
                            </div>
                        </li>
                    </ul>
                </TabsContent>

                <TabsContent value="items" class="mt-4">
                    <div v-if="triggerItems.length === 0" class="rounded-xl border-2 border-dashed border-foreground/25 bg-card p-8 text-center">
                        <p class="text-foreground/60">{{ $t('automations.show.empty_trigger_items') }}</p>
                    </div>
                    <ul v-else class="divide-y rounded-xl border-2 border-foreground/10 bg-card">
                        <li v-for="item in triggerItems" :key="item.id" class="p-4 space-y-2">
                            <div class="flex items-center justify-between gap-4">
                                <p class="font-mono text-xs text-foreground/50 truncate">{{ item.item_key }}</p>
                                <span class="shrink-0 text-xs text-foreground/50">{{ formatDateTime(item.first_seen_at) }}</span>
                            </div>
                            <JsonViewer :value="item.payload" />
                            <div v-if="item.run" class="flex items-center gap-2">
                                <span class="text-xs text-foreground/60">{{ $t('automations.show.run_label') }}:</span>
                                <Badge :variant="runStatusVariant(item.run.status)">{{ item.run.status }}</Badge>
                                <span class="font-mono text-xs text-foreground/50">{{ item.run.id }}</span>
                            </div>
                        </li>
                    </ul>
                </TabsContent>
            </Tabs>
        </div>

        <ConfirmDeleteModal
            ref="deleteModal"
            :title="$t('automations.delete.title')"
            :description="$t('automations.delete.description')"
            :action="$t('automations.delete.confirm')"
            :cancel="$t('automations.delete.cancel')"
        />
    </AppLayout>
</template>
