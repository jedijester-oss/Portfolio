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
    <section class="group mb-6 rounded-2xl border border-noir-border/80 bg-noir-surface/90 p-4 shadow-sm backdrop-blur transition duration-300 hover:shadow-lg">
        <div class="mb-3 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-lg font-semibold text-white">Filter the timeline</h2>
                <p class="text-sm text-slate-400">Refine by era or technology stack.</p>
            </div>
            <button
                v-if="selectedEra || selectedTech"
                class="rounded-full border border-noir-border px-3 py-1 text-sm font-medium text-slate-300 transition hover:border-slate-600 hover:bg-slate-800"
                @click="resetFilters"
            >
                Clear filters
            </button>
        </div>

        <div class="overflow-hidden transition-all duration-300">
            <div class="max-h-1 overflow-hidden opacity-100 transition-all duration-300 group-hover:max-h-[400px] group-hover:opacity-100">
                <div class="space-y-4">
                    <div>
                        <p class="mb-2 text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">Era</p>
                        <div class="flex flex-wrap gap-2">
                            <button
                                v-for="era in eras"
                                :key="era"
                                class="rounded-full px-3 py-1 text-sm font-medium transition"
                                :class="selectedEra === era ? 'bg-emerald-500 text-white' : 'bg-slate-800 text-slate-300 hover:bg-slate-700'"
                                @click="selectEra(era)"
                            >
                                {{ era }}
                            </button>
                        </div>
                    </div>

                    <div>
                        <p class="mb-2 text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">Tech</p>
                        <div class="flex flex-wrap gap-2">
                            <button
                                v-for="tech in techTags"
                                :key="tech"
                                class="rounded-full px-3 py-1 text-sm font-medium transition"
                                :class="selectedTech === tech ? 'bg-emerald-500 text-white' : 'bg-slate-800 text-slate-300 hover:bg-slate-700'"
                                @click="selectTech(tech)"
                            >
                                {{ tech }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
