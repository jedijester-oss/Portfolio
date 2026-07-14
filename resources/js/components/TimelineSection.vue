<script setup>
import { computed, ref } from 'vue';
import { storeToRefs } from 'pinia';
import { useTimelineStore } from '../stores/timelineStore';
import TimelineFilterBar from './TimelineFilterBar.vue';

const store = useTimelineStore();
const { filteredEvents, isLoading, error, selectedTech } = storeToRefs(store);
const brokenImageSlugs = ref(new Set());

const groupedEvents = computed(() => {
    const groups = new Map();

    filteredEvents.value.forEach((event) => {
        const year = event.start_date?.slice(0, 4) || 'Unknown';

        if (!groups.has(year)) {
            groups.set(year, []);
        }

        groups.get(year).push(event);
    });

    return Array.from(groups.entries()).sort(([a], [b]) => Number(b) - Number(a));
});

const toggleTechFilter = (tech) => {
    store.setTech(tech);
};

const isImageBroken = (slug) => brokenImageSlugs.value.has(slug);

const markImageBroken = (slug) => {
    brokenImageSlugs.value.add(slug);
};
</script>

<template>
    <div>
        <TimelineFilterBar />

        <div v-if="isLoading" class="rounded-2xl border border-noir-border/80 bg-noir-surface/90 p-6 text-sm text-slate-300 shadow-sm backdrop-blur">
            Loading timeline events...
        </div>

        <div v-else-if="error" class="rounded-2xl border border-rose-500/40 bg-rose-950/40 p-6 text-sm text-rose-200 shadow-sm backdrop-blur">
            {{ error }}
        </div>

        <div v-else-if="filteredEvents.length === 0" class="rounded-2xl border border-noir-border/80 bg-noir-surface/90 p-6 text-sm text-slate-300 shadow-sm backdrop-blur">
            No timeline entries match the selected filters.
        </div>

        <div v-else class="mt-8 space-y-8">
            <div v-for="[year, events] in groupedEvents" :key="year" class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="h-px flex-1 bg-noir-border"></div>
                    <h2 class="text-sm font-semibold uppercase text-terminal-green text-glow font-mono tracking-widest">{{ year }}</h2>
                    <div class="h-px flex-1 bg-noir-border"></div>
                </div>

                <div class="grid gap-4 lg:grid-cols-2">
                    <article
                        v-for="event in events"
                        :key="event.slug"
                        tabindex="0"
                        class="group shadow-sm transition duration-300 hover:-translate-y-0.5 hover:shadow-md backdrop-blur bg-noir-surface border border-noir-border rounded-xl p-6 box-glow-hover hover:text-terminal-green"
                    >
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-slate-400">{{ event.client_or_company }}</p>
                                <h3 class="mt-1 text-xl font-semibold text-white">{{ event.title }}</h3>
                                <p class="mt-2 text-sm font-medium text-slate-200">{{ event.role }}</p>
                            </div>
                            <span v-if="!event.end_date" class="rounded-full bg-emerald-500/15 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-emerald-300">
                                [ACTIVE]
                            </span>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-2">
                            <button
                                v-for="tech in event.tech_stack"
                                :key="tech"
                                type="button"
                                class="font-medium transitionborder border-terminal-dim/40 text-terminal-green bg-terminal-dark/50 text-xs px-2.5 py-1 rounded-md font-mono text-glow"
                                :class="selectedTech === tech ? 'bg-emerald-500 text-white' : 'bg-slate-800 text-slate-300 hover:bg-slate-700'"
                                @click.stop="toggleTechFilter(tech)"
                            >
                                {{ tech }}
                            </button>
                        </div>

                        <div class="mt-4 overflow-hidden transition-all duration-300">
                            <div class="max-h-none overflow-hidden opacity-100 transition-all duration-300 sm:max-h-0 sm:opacity-0 sm:group-hover:max-h-none sm:group-hover:opacity-100">
                                <div class="grid gap-4 md:grid-cols-[0.8fr_1.6fr]">
                                    <img
                                        v-if="event.metadata?.image && !isImageBroken(event.slug)"
                                        :src="event.metadata.image"
                                        :alt="`${event.title} preview`"
                                        class="h-auto md:h-32 w-full rounded-xl border border-noir-border/60 object-cover md:object-cover"
                                        @error="markImageBroken(event.slug)"
                                    />

                                    <p v-if="event.description" class="text-sm leading-6 text-slate-400">{{ event.description }}</p>
                                </div>

                                <div v-if="[
                                    ['Location', event.metadata?.location],
                                    ['URL', event.metadata?.url],
                                    ['Repository', event.metadata?.repository],
                                    ['Architecture', event.metadata?.architecture_type],
                                    ['IP / Patent', event.metadata?.patent_or_ip],
                                    ['Promotion Timeline', event.metadata?.promotion_timeline],
                                    ['Achievements', event.metadata?.achievements],
                                    ['Products Deployed', event.metadata?.products_deployed],
                                    ['Manager', event.metadata?.manager],
                                    ['Kernel Version', event.metadata?.kernel_version],
                                    ['Hardware Type', event.metadata?.hardware_type],
                                ].filter(([, value]) => value).length" class="mt-4 grid gap-2 rounded-xl border border-noir-border/60 bg-slate-950/40 p-3 text-sm text-slate-300">
                                    <div v-for="item in [
                                        ['Location', event.metadata?.location],
                                        ['URL', event.metadata?.url],
                                        ['Repository', event.metadata?.repository],
                                        ['Architecture', event.metadata?.architecture_type],
                                        ['IP / Patent', event.metadata?.patent_or_ip],
                                        ['Promotion Timeline', event.metadata?.promotion_timeline],
                                        ['Achievements', event.metadata?.achievements],
                                        ['Products Deployed', event.metadata?.products_deployed],
                                        ['Manager', event.metadata?.manager],
                                        ['Kernel Version', event.metadata?.kernel_version],
                                        ['Hardware Type', event.metadata?.hardware_type],
                                    ].filter(([, value]) => value)" :key="item[0]" class="flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between">
                                        <span class="font-medium text-slate-400">{{ item[0] }}</span>
                                        <span class="text-slate-200">
                                            <template v-if="Array.isArray(item[1])">{{ item[1].join(', ') }}</template>
                                            <template v-else>{{ item[1] }}</template>
                                        </span>
                                    </div>
                                </div>

                                <p class="mt-4 text-sm text-slate-500">
                                    {{ event.start_date }} <span v-if="event.end_date">– {{ event.end_date }}</span>
                                </p>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </div>
</template>
