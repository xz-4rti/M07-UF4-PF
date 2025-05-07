<template>
  <div class="login-container">
    <h2>Login</h2>
    <form @submit.prevent="login" class="login-form">
      <input v-model="email" type="email" placeholder="📧 Email" required />
      <input v-model="password" type="password" placeholder="🔒 Password" required />
      <button type="submit">🔐 Login</button>
      <p v-if="error" class="error-message">{{ error }}</p>
    </form>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  data() {
    return {
      email: '',
      password: '',
      error: null,
      loading: false
    };
  },
  methods: {
    async login() {
      this.error = null;
      this.loading = true;
      try {
        const res = await axios.post('/login', {
          email: this.email,
          password: this.password
        });
        const token = res.data.token;
        const userRes = await axios.get('/user', {
          headers: { Authorization: `Bearer ${token}` }
        });
        this.$emit('authenticated', userRes.data, token);
      } catch (e) {
        this.error = e.response?.data?.message || 'Login failed';
      } finally {
        this.loading = false;
      }
    }
  }
};
</script>

<style scoped>
.login-container {
  max-width: 400px;
  margin: 2rem auto;
  padding: 1rem;
  border: 1px solid #ccc;
  border-radius: 6px;
  background-color: #f9f9f9;
}
.login-form input {
  display: block;
  width: 100%;
  margin: 0.5rem 0;
  padding: 0.5rem;
  font-size: 1rem;
}
.login-form button {
  width: 100%;
  padding: 0.5rem;
  font-size: 1rem;
  background-color: #4CAF50;
  color: white;
  border: none;
  border-radius: 4px;
}
.login-form button:disabled {
  background-color: #a5d6a7;
}
.error-message {
  color: red;
  margin-top: 0.5rem;
  font-size: 0.9rem;
}
</style>