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

interface DelayConfig {
    duration: number;
    unit: 'minutes' | 'hours' | 'days';
}

const props = defineProps<{
    data: Record<string, unknown>;
    errors?: Record<string, string>;
}>();
const emit = defineEmits<{ update: [Record<string, unknown>] }>();

const local = ref<DelayConfig>({
    duration: (props.data.duration as number) ?? 1,
    unit: (props.data.unit as DelayConfig['unit']) ?? 'hours',
});

watch(local, (val) => emit('update', val), { deep: true });
</script>

<template>
    <div class="space-y-3">
        <div>
            <label class="mb-1 block text-sm font-medium">{{ $t('automations.config.delay.duration') }}</label>
            <Input type="number" v-model.number="local.duration" placeholder="1" />
            <InputError :message="errors?.duration" class="mt-1" />
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium">{{ $t('automations.config.delay.unit') }}</label>
            <Select v-model="local.unit">
                <SelectTrigger class="w-full">
                    <SelectValue :placeholder="$t('automations.config.select_placeholder')" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="minutes">{{ $t('automations.config.delay.units.minutes') }}</SelectItem>
                    <SelectItem value="hours">{{ $t('automations.config.delay.units.hours') }}</SelectItem>
                    <SelectItem value="days">{{ $t('automations.config.delay.units.days') }}</SelectItem>
                </SelectContent>
            </Select>
            <InputError :message="errors?.unit" class="mt-1" />
        </div>
    </div>
</template>
