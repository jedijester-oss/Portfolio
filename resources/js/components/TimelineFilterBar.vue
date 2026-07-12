<script setup>
import { computed } from 'vue';
import { storeToRefs } from 'pinia';
import { useTimelineStore } from '../stores/timelineStore';

const store = useTimelineStore();
const { events, selectedEra, selectedTech } = storeToRefs(store);

const eras = computed(() => [...new Set(events.value.map((event) => event.era).filter(Boolean))]);
const techTags = computed(() => {
    const tags = new Set();

    events.value.forEach((event) => {
        (event.tech_stack || []).forEach((tech) => tags.add(tech));
    });

    return [...tags].sort();
});

const selectEra = (era) => store.setEra(selectedEra.value === era ? '' : era);
const selectTech = (tech) => store.setTech(tech);
const resetFilters = () => store.clearFilters();
</script>

<template>
    <section class="mb-6 rounded-2xl border border-slate-200 bg-white/80 p-4 shadow-sm backdrop-blur">
        <div class="mb-3 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Filter the timeline</h2>
                <p class="text-sm text-slate-600">Refine by era or technology stack.</p>
            </div>
            <button
                v-if="selectedEra || selectedTech"
                class="rounded-full border border-slate-300 px-3 py-1 text-sm font-medium text-slate-700 transition hover:border-slate-400 hover:bg-slate-50"
                @click="resetFilters"
            >
                Clear filters
            </button>
        </div>

        <div class="space-y-4">
            <div>
                <p class="mb-2 text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Era</p>
                <div class="flex flex-wrap gap-2">
                    <button
                        v-for="era in eras"
                        :key="era"
                        class="rounded-full px-3 py-1 text-sm font-medium transition"
                        :class="selectedEra === era ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                        @click="selectEra(era)"
                    >
                        {{ era }}
                    </button>
                </div>
            </div>

            <div>
                <p class="mb-2 text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Tech</p>
                <div class="flex flex-wrap gap-2">
                    <button
                        v-for="tech in techTags"
                        :key="tech"
                        class="rounded-full px-3 py-1 text-sm font-medium transition"
                        :class="selectedTech === tech ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100'"
                        @click="selectTech(tech)"
                    >
                        {{ tech }}
                    </button>
                </div>
            </div>
        </div>
    </section>
</template>
