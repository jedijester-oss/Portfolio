<script setup>
import { onMounted } from 'vue';
import { storeToRefs } from 'pinia';
import { useTimelineStore } from '../stores/timelineStore';
import AboutMeCard from './AboutMeCard.vue';
import AboutThisSiteCard from './AboutThisSiteCard.vue';
import TimelineSection from './TimelineSection.vue';
import LiveStats from './LiveStats.vue';

const store = useTimelineStore();
const { aboutMe, aboutThisSite } = storeToRefs(store);

// Load the JSON-backed portfolio payload once the page mounts.
onMounted(() => {
    store.fetchEvents();
});
</script>

<template>
    <div class="min-h-screen bg-noir-bg text-slate-100">
        <section class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
            <AboutMeCard :about-me="aboutMe" />
            <TimelineSection />
            <p>&nbsp;</p>
            <AboutThisSiteCard :about-this-site="aboutThisSite" />
            <live-stats />
        </section>
    </div>
</template>
