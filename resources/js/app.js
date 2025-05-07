import { createApp } from 'vue';
import LoginForm from './components/LoginForm.vue';
import ProductTable from './components/ProductTable.vue';

const app = createApp({
    data() {
        return {
            isAuthenticated: false,
            user: null
        }
    },
    components: {
        LoginForm,
        ProductTable
    },
    methods: {
        onAuthenticated(user, token) {
            this.user = user;
            this.isAuthenticated = true;
            localStorage.setItem('token', token);
        }
    },
    mounted() {
        const token = localStorage.getItem('token');
        if (token) {
            axios.get('/api/user', {
                headers: { Authorization: `Bearer ${token}` }
            }).then(res => {
                this.user = res.data;
                this.isAuthenticated = true;
            }).catch(() => {
                localStorage.removeItem('token');
            });
        }
    }
});

import './axios';
app.mount('#app');