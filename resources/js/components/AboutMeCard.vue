<script setup>
import portrait from '../../../public/assets/pr_head_128.jpeg';

defineProps({
    aboutMe: {
        type: Object,
        default: () => ({}),
    },
});
</script>

<template>
    <section tabindex="0" class="group rounded-2xl border border-noir-border/80 bg-noir-surface/90 p-6 shadow-sm backdrop-blur transition duration-300 hover:shadow-lg">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-start">
            <img
                :src="portrait"
                alt="Portrait"
                class="h-24 w-24 rounded-2xl border border-noir-border/80 object-cover shadow-sm"
            />

            <div class="min-w-0">
                <h2 class="text-2xl font-semibold text-white">{{ aboutMe.name || 'About me' }}</h2>

                <div class="mt-4 overflow-hidden text-sm leading-7 text-slate-300 transition-all duration-300">
                    <div class="line-clamp-none sm:line-clamp-2 sm:group-hover:line-clamp-none">
                        <div v-html="aboutMe.summary"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
