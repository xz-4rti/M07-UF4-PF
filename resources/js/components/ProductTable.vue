<template>
  <div>
    <h2>Product List</h2>
    <button v-if="user.role === 'admin'" @click="startCreate" class="action-btn">➕ Add Product</button>

    <div v-if="formVisible" class="form-container">
      <h3>{{ formMode === 'edit' ? 'Edit' : 'Create' }} Product</h3>
      <form @submit.prevent="handleSubmit">
        <input v-model="form.name" placeholder="Name" required />
        <input v-model.number="form.price" type="number" step="0.01" placeholder="Price" required />
        <input v-model.number="form.stock" type="number" placeholder="Stock" required />
        <input v-model="form.description" placeholder="Description" />
        <div class="form-actions">
          <button type="submit" class="save-btn">💾 Save</button>
          <button type="button" class="cancel-btn" @click="cancelForm">Cancel</button>
        </div>
      </form>
      <p v-if="error" class="error-message">{{ error }}</p>
    </div>

    <table>
      <thead>
        <tr>
          <th @click="sortBy('name')">Name</th>
          <th @click="sortBy('price')">Price</th>
          <th>Description</th>
          <th>Stock</th>
          <th v-if="user.role === 'admin'">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="product in sortedProducts" :key="product.id">
          <td>{{ product.name }}</td>
          <td>{{ product.price }}</td>
          <td>{{ product.description }}</td>
          <td>{{ product.stock }}</td>
          <td v-if="user.role === 'admin'">
            <button @click="startEdit(product)" class="action-btn edit">✏️</button>
            <button @click="deleteProduct(product.id)" class="action-btn delete">🗑️</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
  import axios from '../axios';

  export default {
    props: ['user'],
    data() {
      return {
        products: [],
        currentSort: 'name',
        currentSortDir: 'asc',
        formVisible: false,
        formMode: 'create',
        form: {
          id: null,
          name: '',
          price: '',
          stock: '',
          description: ''
        },
        error: null
      };
    },
    computed: {
      sortedProducts() {
        return [...this.products].sort((a, b) => {
          const mod = this.currentSortDir === 'asc' ? 1 : -1;
          if (a[this.currentSort] < b[this.currentSort]) return -1 * mod;
          if (a[this.currentSort] > b[this.currentSort]) return 1 * mod;
          return 0;
        });
      }
    },
    methods: {
      sortBy(column) {
        if (this.currentSort === column) {
          this.currentSortDir = this.currentSortDir === 'asc' ? 'desc' : 'asc';
        } else {
          this.currentSort = column;
          this.currentSortDir = 'asc';
        }
      },
      fetchProducts() {
        axios.get('/products').then(res => this.products = res.data);
      },
      deleteProduct(id) {
        if (confirm('Are you sure you want to delete this product?')) {
          axios.delete(`/products/${id}`).then(() => this.fetchProducts());
        }
      },
      startEdit(product) {
        this.form = { id: product.id, name: product.name, price: product.price, stock: product.stock, description: product.description };
        this.formMode = 'edit';
        this.formVisible = true;
      },
      startCreate() {
        this.form = { id: null, name: '', price: '', stock: '', description: '' };
        this.formMode = 'create';
        this.formVisible = true;
      },
      cancelForm() {
        this.formVisible = false;
        this.form = { id: null, name: '', price: '', stock: '', description: '' };
        this.error = null;
      },
      async handleSubmit() {
        this.error = null;
        const payload = {
          name: this.form.name,
          price: this.form.price,
          stock: this.form.stock,
          description: this.form.description
        };
        try {
          if (this.formMode === 'edit' && this.form.id) {
            await axios.put(`/products/${this.form.id}`, payload);
          } else {
            await axios.post('/products', payload);
          }
          await this.fetchProducts();
          this.cancelForm();
        } catch (err) {
          this.error = err.response?.data?.message || 'Save failed';
        }
      }
    },
    mounted() {
      this.fetchProducts();
    }
  };
</script>

<style scoped>
  /* General Styles */
  body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
  }

  /* Product Table Styles */
  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background-color: white;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }

  table th,
  table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
  }

  table th {
    background-color: #f0f0f0;
    cursor: pointer;
  }

  table th:hover {
    background-color: #e0e0e0;
  }

  table td {
    color: #555;
  }

  button.action-btn {
    background-color: #3498db;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
  }

  button.action-btn:hover {
    background-color: #2980b9;
  }

  button.edit {
    background-color: #f39c12;
  }

  button.edit:hover {
    background-color: #e67e22;
  }

  button.delete {
    background-color: #e74c3c;
  }

  button.delete:hover {
    background-color: #c0392b;
  }

  /* Add Product Button */
  .action-btn.add {
    background-color: #27ae60;
    margin: 20px 0;
    padding: 8px 16px;
    font-size: 16px;
  }

  .action-btn.add:hover {
    background-color: #2ecc71;
  }

  /* Form Modal */
  .form-container {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin: 20px 0;
  }

  h3 {
    margin-top: 0;
  }

  input {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 4px;
    border: 1px solid #ddd;
    box-sizing: border-box;
    font-size: 14px;
  }

  input[type="number"] {
    -moz-appearance: textfield;
  }

  input:focus {
    outline: none;
    border-color: #3498db;
    box-shadow: 0 0 5px rgba(52, 152, 219, 0.2);
  }

  .form-actions {
    display: flex;
    justify-content: space-between;
  }

  .save-btn,
  .cancel-btn {
    padding: 8px 16px;
    font-size: 14px;
    border-radius: 4px;
    border: none;
    cursor: pointer;
  }

  .save-btn {
    background-color: #3498db;
    color: white;
  }

  .save-btn:hover {
    background-color: #2980b9;
  }

  .cancel-btn {
    background-color: #e0e0e0;
    color: #333;
  }

  .cancel-btn:hover {
    background-color: #ccc;
  }

  /* Error Message */
  .error-message {
    color: #e74c3c;
    font-size: 14px;
    margin-top: 10px;
    font-weight: bold;
  }

  /* Responsiveness */
  @media (max-width: 768px) {

    table th,
    table td {
      font-size: 12px;
      padding: 8px;
    }

    input {
      padding: 8px;
      font-size: 14px;
    }

    .form-actions {
      flex-direction: column;
    }

    .save-btn,
    .cancel-btn {
      width: 100%;
      margin: 5px 0;
    }
  }
</style>