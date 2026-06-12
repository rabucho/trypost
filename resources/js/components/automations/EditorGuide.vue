<script setup lang="ts">
import {
    Sheet,
    SheetContent,
    SheetDescription,
    SheetHeader,
    SheetTitle,
} from '@/components/ui/sheet';

const open = defineModel<boolean>('open', { default: false });

// The `{{ ... }}` snippets stay literal (they're code); only the short
// descriptions are translated.
const dataRefs = [
    { code: '{{ trigger.post.content }}', descKey: 'automations.guide.refs.trigger_post' },
    { code: '{{ fetched.title }}', descKey: 'automations.guide.refs.fetched_title' },
    { code: '{{ fetched.link }}', descKey: 'automations.guide.refs.fetched_link' },
    { code: '{{ generated.content }}', descKey: 'automations.guide.refs.generated' },
    { code: '{{ now }}', descKey: 'automations.guide.refs.now' },
];

const variableExample = '{{ variables.API_KEY }}';
</script>

<template>
    <Sheet v-model:open="open">
        <SheetContent side="right" class="w-full overflow-y-auto sm:max-w-md">
            <SheetHeader>
                <SheetTitle>{{ $t('automations.guide.title') }}</SheetTitle>
                <SheetDescription>{{ $t('automations.guide.subtitle') }}</SheetDescription>
            </SheetHeader>

            <div class="space-y-6 px-4 pb-8">
                <section class="space-y-1.5">
                    <h3 class="text-[11px] font-black uppercase tracking-widest text-foreground/50">
                        {{ $t('automations.guide.flow_title') }}
                    </h3>
                    <p class="text-sm text-foreground/70">{{ $t('automations.guide.flow_text') }}</p>
                </section>

                <section class="space-y-2">
                    <h3 class="text-[11px] font-black uppercase tracking-widest text-foreground/50">
                        {{ $t('automations.guide.data_title') }}
                    </h3>
                    <p class="text-sm text-foreground/70">{{ $t('automations.guide.data_text') }}</p>
                    <div class="space-y-1.5">
                        <div
                            v-for="ref in dataRefs"
                            :key="ref.code"
                            class="flex flex-col gap-0.5 rounded-lg border-2 border-foreground/15 bg-card/50 p-2.5"
                        >
                            <code class="font-mono text-xs font-bold text-foreground">{{ ref.code }}</code>
                            <span class="text-xs text-foreground/55">{{ $t(ref.descKey) }}</span>
                        </div>
                    </div>
                </section>

                <section class="space-y-2">
                    <h3 class="text-[11px] font-black uppercase tracking-widest text-foreground/50">
                        {{ $t('automations.guide.vars_title') }}
                    </h3>
                    <p class="text-sm text-foreground/70">{{ $t('automations.guide.vars_text') }}</p>
                    <code class="inline-block rounded-lg border-2 border-foreground/15 bg-card/50 px-2.5 py-1.5 font-mono text-xs font-bold text-foreground">{{ variableExample }}</code>
                </section>

                <section class="rounded-xl border-2 border-foreground bg-amber-50 p-3 shadow-[3px_3px_0_var(--foreground)]">
                    <p class="text-sm font-medium text-foreground">💡 {{ $t('automations.guide.tip_text') }}</p>
                </section>
            </div>
        </SheetContent>
    </Sheet>
</template>
