import { defineStore } from 'pinia';
import axios from 'axios';

export const useTimelineStore = defineStore('timeline', {
    state: () => ({
        events: [],
        aboutMe: {},
        aboutThisSite: {},
        selectedEra: '',
        selectedTech: '',
        isLoading: false,
        error: null,
    }),

    getters: {
        filteredEvents(state) {
            const events = Array.isArray(state.events) ? state.events : [];

            return events.filter((event) => {
                const matchesEra = !state.selectedEra || event.era === state.selectedEra;
                const matchesTech = !state.selectedTech || (event.tech_stack || []).includes(state.selectedTech);

                return matchesEra && matchesTech;
            });
        },

        featuredEvents(state) {
            const events = Array.isArray(state.events) ? state.events : [];

            return events.filter((event) => Boolean(event.featured)).slice(0, 6);
        },
    },

    actions: {
        /**
         * Fetch the JSON-backed portfolio payload and normalize it for the UI.
         */
        async fetchEvents() {
            this.isLoading = true;
            this.error = null;

            try {
                const { data } = await axios.get('/api/timeline');
                const payload = data && typeof data === 'object' ? data : {};
                const normalizedEvents = Array.isArray(payload.events)
                    ? payload.events
                    : Array.isArray(payload)
                        ? payload
                        : [];

                this.events = normalizedEvents;
                this.aboutMe = payload.about_me || {};
                this.aboutThisSite = payload.about_this_site || {};
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
