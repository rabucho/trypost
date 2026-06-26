<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import {
    IconArrowRight,
    IconCalendar,
    IconChartBar,
    IconCheck,
    IconClock,
    IconCoin,
    IconCompass,
    IconDots,
    IconPalette,
    IconRobot,
    IconSparkles,
    IconTrendingUp,
    IconUsers,
    IconUsersGroup,
} from '@tabler/icons-vue';
import { trans } from 'laravel-vue-i18n';
import type { FunctionalComponent } from 'vue';

import { Button } from '@/components/ui/button';
import { store } from '@/routes/app/onboarding/goals';

const props = defineProps<{
    goals: string[];
    selected?: string[] | null;
}>();

const EXCLUSIVE_GOAL = 'just_exploring';

const form = useForm<{ goals: string[] }>({ goals: props.selected ?? [] });

const goalMeta: Record<string, { icon: FunctionalComponent; color: string }> = {
    save_time: { icon: IconClock, color: 'text-amber-600' },
    ai_content: { icon: IconSparkles, color: 'text-violet-700' },
    plan_calendar: { icon: IconCalendar, color: 'text-blue-700' },
    stay_on_brand: { icon: IconPalette, color: 'text-orange-600' },
    grow_audience: { icon: IconTrendingUp, color: 'text-rose-600' },
    drive_sales: { icon: IconCoin, color: 'text-emerald-600' },
    manage_clients: { icon: IconUsersGroup, color: 'text-cyan-600' },
    team_collaboration: { icon: IconUsers, color: 'text-fuchsia-600' },
    automate_api: { icon: IconRobot, color: 'text-teal-600' },
    track_performance: { icon: IconChartBar, color: 'text-indigo-600' },
    just_exploring: { icon: IconCompass, color: 'text-sky-600' },
    other: { icon: IconDots, color: 'text-foreground' },
};

const goalIcon = (value: string): FunctionalComponent => goalMeta[value]?.icon ?? IconDots;

const goalColor = (value: string): string => goalMeta[value]?.color ?? 'text-foreground';

const goalLabel = (value: string): string => trans(`onboarding.goals.${value}`);

const isSelected = (value: string): boolean => form.goals.includes(value);

const toggle = (value: string): void => {
    if (value === EXCLUSIVE_GOAL) {
        form.goals = isSelected(value) ? [] : [value];

        return;
    }

    const withoutExclusive = form.goals.filter((goal) => goal !== EXCLUSIVE_GOAL);

    form.goals = isSelected(value)
        ? withoutExclusive.filter((goal) => goal !== value)
        : [...withoutExclusive, value];
};

const submit = (): void => {
    if (form.goals.length === 0 || form.processing) {
        return;
    }

    form.post(store.url());
};
</script>

<template>
    <Head :title="$t('onboarding.goals_title')" />

    <section class="relative min-h-screen overflow-hidden bg-background">
        <div
            class="pointer-events-none absolute inset-0 opacity-[0.06]"
            style="background-image: radial-gradient(circle, #0a0a0a 1px, transparent 1px); background-size: 28px 28px;"
        />
        <div class="pointer-events-none absolute -top-20 right-0 size-[560px] rounded-full bg-violet-200/50 blur-3xl" />

        <div class="relative mx-auto flex min-h-screen max-w-3xl flex-col justify-center px-6 py-12">
            <div class="mx-auto mb-10 max-w-xl space-y-3 text-center">
                <h1
                    class="text-balance text-3xl font-normal leading-[1.1] tracking-tight text-foreground sm:text-4xl"
                    style="font-family: var(--font-display);"
                >
                    {{ $t('onboarding.goals_title') }}
                </h1>
                <p class="text-balance text-base text-muted-foreground">
                    {{ $t('onboarding.goals_description') }}
                </p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <button
                    v-for="goal in goals"
                    :key="goal"
                    type="button"
                    :class="[
                        'relative flex cursor-pointer flex-col items-start gap-3 rounded-2xl border-2 border-foreground p-5 text-left shadow-2xs transition-shadow hover:shadow-md',
                        isSelected(goal) ? 'bg-violet-100' : 'bg-card',
                    ]"
                    @click="toggle(goal)"
                >
                    <span class="inline-flex size-10 items-center justify-center rounded-2xl border-2 border-foreground bg-card shadow-2xs">
                        <component
                            :is="goalIcon(goal)"
                            :class="[goalColor(goal), 'size-5']"
                            stroke-width="2.25"
                        />
                    </span>
                    <span class="text-base font-bold tracking-tight text-foreground">
                        {{ goalLabel(goal) }}
                    </span>
                    <span
                        v-if="isSelected(goal)"
                        class="absolute right-4 top-4 inline-flex size-5 items-center justify-center rounded-full border-2 border-foreground bg-foreground"
                    >
                        <IconCheck class="size-3 text-background" stroke-width="3" />
                    </span>
                </button>
            </div>

            <div class="mx-auto mt-10 flex w-full max-w-sm flex-col items-center gap-3">
                <Button
                    type="button"
                    size="lg"
                    class="w-full rounded-full"
                    :disabled="form.goals.length === 0 || form.processing"
                    @click="submit"
                >
                    {{ $t('onboarding.continue') }}
                    <IconArrowRight class="size-4" />
                </Button>
            </div>
        </div>
    </section>
</template>
