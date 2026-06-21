import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

import type { Usage } from '@/types';

interface Plan {
    id: string;
    slug: string;
    name: string;
}

interface Features {
    monthlyCreditsLimit: number;
}

export const useFeatureAccess = () => {
    const page = usePage();

    const isSelfHosted = computed(() => page.props.selfHosted as boolean);
    const plan = computed<Plan | null>(() => (page.props.auth as { plan: Plan | null }).plan ?? null);
    const usage = computed<Usage | null>(() => (page.props.usage as Usage | null) ?? null);
    const features = computed<Features | null>(() => (page.props.features as Features | null) ?? null);

    const monthlyCreditsLimit = computed(() => features.value?.monthlyCreditsLimit ?? 0);

    const canCreateWorkspace = computed(() => true);
    const canConnectSocialAccount = computed(() => true);
    const canInviteMember = computed(() => true);

    const hasCreditsLeft = computed(() => {
        if (isSelfHosted.value) return true;
        if (!usage.value) return true;
        return usage.value.creditsUsed < monthlyCreditsLimit.value;
    });

    return {
        plan,
        usage,
        features,
        isSelfHosted,
        monthlyCreditsLimit,
        canCreateWorkspace,
        canConnectSocialAccount,
        canInviteMember,
        hasCreditsLeft,
    };
};
