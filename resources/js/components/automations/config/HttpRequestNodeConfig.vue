<script setup lang="ts">
import { computed, ref, watch } from 'vue';

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

type Method = 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE';
type AuthType = 'none' | 'bearer' | 'basic' | 'api_key';

interface HttpRequestConfig {
    url: string;
    method: Method;
    auth_type: AuthType;
    auth_token: string;
    auth_username: string;
    auth_password: string;
    auth_header_name: string;
    body_template: string;
    items_path: string;
    item_key_path: string;
    item_date_path: string;
}

const props = defineProps<{
    data: Record<string, unknown>;
    errors?: Record<string, string>;
}>();
const emit = defineEmits<{ update: [Record<string, unknown>] }>();

const local = ref<HttpRequestConfig>({
    url: (props.data.url as string) ?? '',
    method: (props.data.method as Method) ?? 'GET',
    auth_type: (props.data.auth_type as AuthType) ?? 'none',
    auth_token: (props.data.auth_token as string) ?? '',
    auth_username: (props.data.auth_username as string) ?? '',
    auth_password: (props.data.auth_password as string) ?? '',
    auth_header_name: (props.data.auth_header_name as string) ?? 'X-API-Key',
    body_template: (props.data.body_template as string) ?? '',
    items_path: (props.data.items_path as string) ?? '',
    item_key_path: (props.data.item_key_path as string) ?? '',
    item_date_path: (props.data.item_date_path as string) ?? '',
});

watch(local, (val) => emit('update', val), { deep: true });

const supportsBody = computed(() => ['POST', 'PUT', 'PATCH'].includes(local.value.method));
const isPollingMode = computed(() => local.value.items_path.trim() !== '');
</script>

<template>
    <div class="space-y-4">
        <div class="grid grid-cols-[110px_1fr] gap-2">
            <div>
                <label class="mb-1 block text-sm font-medium">{{ $t('automations.config.http_request.method') }}</label>
                <Select v-model="local.method">
                    <SelectTrigger class="w-full">
                        <SelectValue />
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
                <label class="mb-1 block text-sm font-medium">{{ $t('automations.config.http_request.url') }}</label>
                <Input v-model="local.url" placeholder="https://api.example.com/items" />
                <InputError :message="errors?.url" class="mt-1" />
            </div>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium">{{ $t('automations.config.http_request.auth_type') }}</label>
            <Select v-model="local.auth_type">
                <SelectTrigger class="w-full">
                    <SelectValue />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="none">{{ $t('automations.config.http_request.auth.none') }}</SelectItem>
                    <SelectItem value="bearer">{{ $t('automations.config.http_request.auth.bearer') }}</SelectItem>
                    <SelectItem value="basic">{{ $t('automations.config.http_request.auth.basic') }}</SelectItem>
                    <SelectItem value="api_key">{{ $t('automations.config.http_request.auth.api_key') }}</SelectItem>
                </SelectContent>
            </Select>
        </div>

        <div v-if="local.auth_type === 'bearer'">
            <label class="mb-1 block text-sm font-medium">{{ $t('automations.config.http_request.bearer_token') }}</label>
            <Input v-model="local.auth_token" type="password" autocomplete="off" placeholder="sk-…" />
            <InputError :message="errors?.auth_token" class="mt-1" />
        </div>

        <template v-if="local.auth_type === 'basic'">
            <div>
                <label class="mb-1 block text-sm font-medium">{{ $t('automations.config.http_request.basic_username') }}</label>
                <Input v-model="local.auth_username" autocomplete="off" />
                <InputError :message="errors?.auth_username" class="mt-1" />
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">{{ $t('automations.config.http_request.basic_password') }}</label>
                <Input v-model="local.auth_password" type="password" autocomplete="off" />
                <InputError :message="errors?.auth_password" class="mt-1" />
            </div>
        </template>

        <template v-if="local.auth_type === 'api_key'">
            <div>
                <label class="mb-1 block text-sm font-medium">{{ $t('automations.config.http_request.api_key_header') }}</label>
                <Input v-model="local.auth_header_name" placeholder="X-API-Key" />
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">{{ $t('automations.config.http_request.api_key_value') }}</label>
                <Input v-model="local.auth_token" type="password" autocomplete="off" />
                <InputError :message="errors?.auth_token" class="mt-1" />
            </div>
        </template>

        <div v-if="supportsBody">
            <label class="mb-1 block text-sm font-medium">{{ $t('automations.config.http_request.body_template') }}</label>
            <Textarea v-model="local.body_template" :rows="5" placeholder='{"id": "{{ trigger.post.id }}"}' />
            <InputError :message="errors?.body_template" class="mt-1" />
        </div>

        <div class="border-t-2 border-foreground/10 pt-4">
            <p class="mb-2 text-[11px] font-black uppercase tracking-widest text-foreground/60">
                {{ $t('automations.config.http_request.polling_section') }}
            </p>
            <p class="mb-3 text-xs text-foreground/60">
                {{ $t('automations.config.http_request.polling_hint') }}
            </p>

            <div class="space-y-3">
                <div>
                    <label class="mb-1 block text-sm font-medium">{{ $t('automations.config.http_request.items_path') }}</label>
                    <Input v-model="local.items_path" placeholder="data.items" />
                    <InputError :message="errors?.items_path" class="mt-1" />
                </div>
                <template v-if="isPollingMode">
                    <div>
                        <label class="mb-1 block text-sm font-medium">{{ $t('automations.config.http_request.item_key_path') }}</label>
                        <Input v-model="local.item_key_path" placeholder="id" />
                        <InputError :message="errors?.item_key_path" class="mt-1" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium">{{ $t('automations.config.http_request.item_date_path') }}</label>
                        <Input v-model="local.item_date_path" placeholder="published_at" />
                        <InputError :message="errors?.item_date_path" class="mt-1" />
                        <p class="mt-1 text-xs text-foreground/50">{{ $t('automations.config.http_request.item_date_path_hint') }}</p>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>
