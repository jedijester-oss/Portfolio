import { defineStore } from 'pinia';
import axios from 'axios';

export const useTimelineStore = defineStore('timeline', {
    state: () => ({
        events: [],
        selectedEra: '',
        selectedTech: '',
        isLoading: false,
        error: null,
    }),

    getters: {
        filteredEvents(state) {
            return state.events.filter((event) => {
                const matchesEra = !state.selectedEra || event.era === state.selectedEra;
                const matchesTech = !state.selectedTech || (event.tech_stack || []).includes(state.selectedTech);

                return matchesEra && matchesTech;
            });
        },

        featuredEvents(state) {
            return state.events.filter((event) => Boolean(event.featured)).slice(0, 6);
        },
    },

    actions: {
        async fetchEvents() {
            this.isLoading = true;
            this.error = null;

            try {
                const { data } = await axios.get('/api/timeline');
                this.events = data;
            } catch (error) {
                this.error = error.message || 'Unable to load timeline events.';
            } finally {
                this.isLoading = false;
            }
        },

        setEra(era) {
            this.selectedEra = era;
        },

        setTech(tech) {
            this.selectedTech = tech === this.selectedTech ? '' : tech;
        },

        clearFilters() {
            this.selectedEra = '';
            this.selectedTech = '';
        },
    },
});
