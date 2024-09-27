import './bootstrap';
import { createApp } from 'vue';
import TaskList from './components/taskAssigner.vue';

const app = createApp({
    components: {
        'task-list': TaskList
    }
});

app.mount('#app');
