<script setup lang="ts">
import { ref, watch } from 'vue';

import InputError from '@/components/InputError.vue';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';

interface WebhookConfig {
    url: string;
    method: 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE';
    headers?: Record<string, string>;
    payload_template: string;
}

const props = defineProps<{
    data: Record<string, unknown>;
    errors?: Record<string, string>;
}>();
const emit = defineEmits<{ update: [Record<string, unknown>] }>();

const local = ref<WebhookConfig>({
    url: (props.data.url as string) ?? '',
    method: (props.data.method as WebhookConfig['method']) ?? 'POST',
    headers: (props.data.headers as Record<string, string>) ?? {},
    payload_template: (props.data.payload_template as string) ?? '{}',
});

watch(local, (val) => emit('update', val), { deep: true });
</script>

<template>
    <div class="space-y-3">
        <div>
            <label class="mb-1 block text-sm font-medium">{{ $t('automations.config.webhook.url') }}</label>
            <Input v-model="local.url" placeholder="https://hooks.example.com/…" />
            <InputError :message="errors?.url" class="mt-1" />
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium">{{ $t('automations.config.webhook.method') }}</label>
            <Select v-model="local.method">
                <SelectTrigger class="w-full">
                    <SelectValue :placeholder="$t('automations.config.select_placeholder')" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="GET">GET</SelectItem>
                    <SelectItem value="POST">POST</SelectItem>
                    <SelectItem value="PUT">PUT</SelectItem>
                    <SelectItem value="PATCH">PATCH</SelectItem>
                    <SelectItem value="DELETE">DELETE</SelectItem>
                </SelectContent>
            </Select>
            <InputError :message="errors?.method" class="mt-1" />
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium">{{ $t('automations.config.webhook.payload_template') }}</label>
            <Textarea v-model="local.payload_template" :rows="6" placeholder='{"content": "{{ post.content }}"}' />
            <InputError :message="errors?.payload_template" class="mt-1" />
        </div>
    </div>
</template>
