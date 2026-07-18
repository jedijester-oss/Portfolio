<script setup>
import { ref, onMounted } from 'vue';

const metrics = ref({
    total_hits: 'LOADING...',
    unique_nodes: 'LOADING...',
    active_connections: 'LOADING...'
});

onMounted(async () => {
    // 1. Grab baseline stats instantly on load
    try {
        const response = await fetch('/api/stats');
        const data = await response.json();
        metrics.value = data;
    } catch (e) {
        console.error("Failed to seed initial telemetry data.");
    }

    // 2. Keep listening for real-time changes
    if (window.Echo) {
        window.Echo.channel('terminal-metrics')
            .listen('LiveStatsUpdated', (e) => {
                metrics.value.total_hits = e.total_hits;
                metrics.value.unique_nodes = e.unique_nodes;
                metrics.value.active_connections = e.active_connections;
            });
    }
});
</script>

<template>
    <div class="font-mono text-terminal-green text-sm space-y-1 bg-black/40 p-4 border border-terminal-dim/30 rounded box-glow">
        <div class="text-xs uppercase tracking-wider text-terminal-dim mb-2">// TELEMETRY_STREAM ACTIVE //</div>
        <div>TOTAL_LOGGED_HITS: <span class="text-white text-glow-intense">{{ metrics.total_hits }}</span></div>
        <div>UNIQUE_RESOLVED_NODES: <span class="text-white text-glow-intense">{{ metrics.unique_nodes }}</span></div>
        <div>ACTIVE_WEBSOCKETS: <span class="text-white text-glow-intense">{{ metrics.active_connections }}</span></div>
    </div>
</template>