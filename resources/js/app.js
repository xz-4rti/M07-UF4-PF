import { createApp } from 'vue';
import ProductTable from './components/ProductTable.vue';
import axios from 'axios'

createApp({
    components: { ProductTable }
}).mount('#app');

axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;
axios.get('/sanctum/csrf-cookie');
