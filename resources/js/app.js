
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import './echo';
import TimelineIndex from './components/TimelineIndex.vue';

const app = createApp(TimelineIndex);
const pinia = createPinia();

app.use(pinia);
app.mount('#app');

