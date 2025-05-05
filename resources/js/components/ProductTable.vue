<template>
  <div>
    <h2>Product List</h2>
    <table border="1" cellpadding="5">
      <thead>
        <tr>
          <th @click="sortBy('name')">Name</th>
          <th @click="sortBy('price')">Price</th>
          <th>Description</th>
          <th v-if="isAdmin">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="product in sortedProducts" :key="product.id">
          <td>{{ product.name }}</td>
          <td>{{ product.price }}</td>
          <td>{{ product.description }}</td>
          <td v-if="isAdmin">
            <button @click="editProduct(product)">Edit</button>
            <button @click="deleteProduct(product.id)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>

    <div v-if="isAdmin">
      <h3>{{ form.id ? 'Edit' : 'Create' }} Product</h3>
      <form @submit.prevent="saveProduct">
        <input v-model="form.name" placeholder="Name" required />
        <input v-model="form.price" placeholder="Price" type="number" required />
        <textarea v-model="form.description" placeholder="Description"></textarea>
        <button type="submit">Save</button>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      products: [],
      sortKey: 'name',
      sortAsc: true,
      form: {
        id: null,
        name: '',
        price: '',
        description: ''
      },
      isAdmin: false
    };
  },
  computed: {
    sortedProducts() {
      return [...this.products].sort((a, b) => {
        const modifier = this.sortAsc ? 1 : -1;
        return a[this.sortKey] > b[this.sortKey] ? modifier : -modifier;
      });
    }
  },
  methods: {
    async fetchUserRole() {
      try {
        const response = await axios.get('/api/user', {
          headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
        });
        this.isAdmin = response.data.role === 'admin';
      } catch (err) {
        console.error(err);
      }
    },
    async fetchProducts() {
      const response = await axios.get('/api/products');
      this.products = response.data;
    },
    async saveProduct() {
      const headers = {
        Authorization: `Bearer ${localStorage.getItem('token')}`
      };
      try {
        if (this.form.id) {
          // update
          await axios.put(`/api/products/${this.form.id}`, this.form, { headers });
        } else {
          // create
          await axios.post('/api/products', this.form, { headers });
        }
        this.form = { id: null, name: '', price: '', description: '' };
        this.fetchProducts();
      } catch (err) {
        console.error('Save failed', err.response?.data);
      }
    },
    editProduct(product) {
      this.form = { ...product };
    },
    async deleteProduct(id) {
      if (!confirm('Are you sure?')) return;
      const headers = {
        Authorization: `Bearer ${localStorage.getItem('token')}`
      };
      try {
        await axios.delete(`/api/products/${id}`, { headers });
        this.fetchProducts();
      } catch (err) {
        console.error('Delete failed', err.response?.data);
      }
    },
    sortBy(key) {
      this.sortAsc = this.sortKey === key ? !this.sortAsc : true;
      this.sortKey = key;
    }
  },
  mounted() {
    this.fetchUserRole();
    this.fetchProducts();
  }
};
</script>
