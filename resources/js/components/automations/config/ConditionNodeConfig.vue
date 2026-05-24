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

interface ConditionConfig {
    field: string;
    operator: string;
    value: string;
}

const props = defineProps<{
    data: Record<string, unknown>;
    errors?: Record<string, string>;
}>();
const emit = defineEmits<{ update: [Record<string, unknown>] }>();

const local = ref<ConditionConfig>({
    field: (props.data.field as string) ?? '',
    operator: (props.data.operator as string) ?? 'contains',
    value: (props.data.value as string) ?? '',
});

watch(local, (val) => emit('update', val), { deep: true });
</script>

<template>
    <div class="space-y-3">
        <div>
            <label class="mb-1 block text-sm font-medium">{{ $t('automations.config.condition.field') }}</label>
            <Input v-model="local.field" placeholder="{{ trigger.title }}" />
            <InputError :message="errors?.field" class="mt-1" />
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium">{{ $t('automations.config.condition.operator') }}</label>
            <Select v-model="local.operator">
                <SelectTrigger class="w-full">
                    <SelectValue :placeholder="$t('automations.config.select_placeholder')" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="contains">{{ $t('automations.config.condition.operators.contains') }}</SelectItem>
                    <SelectItem value="not_contains">{{ $t('automations.config.condition.operators.not_contains') }}</SelectItem>
                    <SelectItem value="equals">{{ $t('automations.config.condition.operators.equals') }}</SelectItem>
                    <SelectItem value="not_equals">{{ $t('automations.config.condition.operators.not_equals') }}</SelectItem>
                    <SelectItem value="matches">{{ $t('automations.config.condition.operators.matches') }}</SelectItem>
                    <SelectItem value="greater_than">{{ $t('automations.config.condition.operators.greater_than') }}</SelectItem>
                    <SelectItem value="less_than">{{ $t('automations.config.condition.operators.less_than') }}</SelectItem>
                </SelectContent>
            </Select>
            <InputError :message="errors?.operator" class="mt-1" />
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium">{{ $t('automations.config.condition.value') }}</label>
            <Input v-model="local.value" placeholder="keyword" />
            <InputError :message="errors?.value" class="mt-1" />
        </div>
    </div>
</template>
