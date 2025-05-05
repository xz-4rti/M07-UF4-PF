<template>
    <form @submit.prevent="login">
        <h2>Login</h2>
        <input v-model="email" type="email" placeholder="Email" required>
        <input v-model="password" type="password" placeholder="Password" required>
        <button type="submit">Login</button>
        <p v-if="error" style="color:red;">{{ error }}</p>
    </form>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            email: '',
            password: '',
            error: '',
        };
    },
    methods: {
        async login() {
            try {
                await axios.get('/sanctum/csrf-cookie');
                const response = await axios.post('/api/login', {
                    email: this.email,
                    password: this.password,
                });
                localStorage.setItem('token', response.data.token);
                window.location.href = '/products'; // Redirect
            } catch (err) {
                this.error = 'Invalid credentials.';
            }
        }
    }
}
</script>
