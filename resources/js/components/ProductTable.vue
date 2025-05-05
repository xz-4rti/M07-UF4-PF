<template>
  <div>
    <h1>Product List</h1>
    <p v-if="loading">Loading...</p>
    <table v-if="!loading">
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
            <button @click="deleteProduct(product.id)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const products = ref([])
const loading = ref(true)
const sortKey = ref('name')
const sortAsc = ref(true)
const isAdmin = ref(false)

const sortedProducts = computed(() => {
  return [...products.value].sort((a, b) => {
    if (a[sortKey.value] < b[sortKey.value]) return sortAsc.value ? -1 : 1
    if (a[sortKey.value] > b[sortKey.value]) return sortAsc.value ? 1 : -1
    return 0
  })
})

function sortBy(key) {
  if (sortKey.value === key) sortAsc.value = !sortAsc.value
  else sortKey.value = key
}

async function fetchProducts() {
  try {
    const res = await axios.get('/api/products')
    products.value = res.data
  } catch (e) {
    alert("Not authenticated or error fetching products.")
  } finally {
    loading.value = false
  }
}

async function fetchUser() {
  const res = await axios.get('/api/user')
  isAdmin.value = res.data.role === 'admin'
}

async function deleteProduct(id) {
  if (confirm('Are you sure?')) {
    await axios.delete(`/api/products/${id}`)
    products.value = products.value.filter(p => p.id !== id)
  }
}

onMounted(() => {
  fetchUser()
  fetchProducts()
})
</script>

<style scoped>
th {
  cursor: pointer;
}
</style>
