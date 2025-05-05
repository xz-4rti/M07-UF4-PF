import { createApp } from 'vue';
import LoginForm from './components/LoginForm.vue';
import ProductTable from './components/ProductTable.vue';

const app = createApp({});
app.component('login-form', LoginForm);
app.component('product-table', ProductTable);
app.mount('#app');
