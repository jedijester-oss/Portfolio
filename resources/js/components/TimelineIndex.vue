<script setup>
import { computed, onMounted } from 'vue';
import { storeToRefs } from 'pinia';
import { useTimelineStore } from '../stores/timelineStore';
import TimelineFilterBar from './TimelineFilterBar.vue';

const store = useTimelineStore();
const { filteredEvents, isLoading, error, selectedTech } = storeToRefs(store);

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

onMounted(() => {
    store.fetchEvents();
});

const toggleTechFilter = (tech) => {
    store.setTech(tech);
};
</script>

<template>
    <section class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-8">
            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-slate-500">Developer timeline</p>
            <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl">A chronological view of my engineering work</h1>
            <p class="mt-3 max-w-2xl text-base text-slate-600">Explore the projects, companies, and technical shifts that shaped my career.</p>
        </div>

        <TimelineFilterBar />

        <div v-if="isLoading" class="rounded-2xl border border-slate-200 bg-white p-6 text-sm text-slate-600 shadow-sm">
            Loading timeline events...
        </div>

        <div v-else-if="error" class="rounded-2xl border border-rose-200 bg-rose-50 p-6 text-sm text-rose-700 shadow-sm">
            {{ error }}
        </div>

        <div v-else-if="filteredEvents.length === 0" class="rounded-2xl border border-slate-200 bg-white p-6 text-sm text-slate-600 shadow-sm">
            No timeline entries match the selected filters.
        </div>

        <div v-else class="space-y-8">
            <div v-for="[year, events] in groupedEvents" :key="year" class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="h-px flex-1 bg-slate-200"></div>
                    <h2 class="text-sm font-semibold uppercase tracking-[0.25em] text-slate-500">{{ year }}</h2>
                    <div class="h-px flex-1 bg-slate-200"></div>
                </div>

                <div class="grid gap-4 lg:grid-cols-2">
                    <article
                        v-for="event in events"
                        :key="event.slug"
                        class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md"
                    >
                        <div class="mb-4 flex items-start justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-slate-500">{{ event.client_or_company }}</p>
                                <h3 class="mt-1 text-xl font-semibold text-slate-900">{{ event.title }}</h3>
                            </div>
                            <span v-if="!event.end_date" class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">
                                [ ACTIVE NOW ]
                            </span>
                        </div>

                        <p class="mb-4 text-sm font-medium text-slate-700">{{ event.role }}</p>
                        <p class="mb-4 text-sm leading-6 text-slate-600">{{ event.tagline }}</p>

                        <div class="mb-4 flex flex-wrap gap-2">
                            <button
                                v-for="tech in event.tech_stack"
                                :key="tech"
                                type="button"
                                class="rounded-full px-3 py-1 text-sm font-medium transition"
                                :class="selectedTech === tech ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                                @click.stop="toggleTechFilter(tech)"
                            >
                                {{ tech }}
                            </button>
                        </div>

                        <p class="text-sm text-slate-500">
                            {{ event.start_date }} <span v-if="event.end_date">– {{ event.end_date }}</span>
                        </p>
                    </article>
                </div>
            </div>
        </div>
    </section>
</template>
