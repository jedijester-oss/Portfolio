<script setup>
defineProps({
    aboutThisSite: {
        type: Object,
        default: () => ({}),
    },
});
</script>

<template>
    <section class="rounded-2xl border border-noir-border/80 bg-noir-surface/90 p-6 shadow-sm backdrop-blur">
        <p class="text-sm font-semibold uppercase tracking-[0.25em] text-slate-400">About this site</p>
        <h2 class="mt-2 text-2xl font-semibold text-white">{{ aboutThisSite.title || 'About this site' }}</h2>
        <div class="mt-4 text-sm leading-7 text-slate-300" v-html="aboutThisSite.summary"></div>
    </section>
</template>
