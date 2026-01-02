<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../api'; // Import your new axios instance


const router = useRouter();
const theses = ref([]);
const isLoading = ref(true);
const errorMessage = ref('');


const fetchTheses = async () => {
  isLoading.value = true;
  try {
    // Call Laravel API
    const response = await api.get('/archives'); 
    theses.value = response.data; // Laravel returns array directly
  } catch (err) {
    console.error('Error:', err);
    errorMessage.value = "Failed to load data";
  } finally {
    isLoading.value = false;
  }
};

onMounted(() => {
  fetchTheses();
});
</script>

<template>
  <div class="dashboard-container">
    <header class="header">
      <h1>📚 Thesis Archive</h1>
      <div class="buttons">
        <button @click="router.push('/upload')" class="upload-btn">+ Upload New Thesis</button>
        <button @click="router.push('/login')" class="logout-btn">Logout</button>
      </div>
    </header>

    <main class="content">
      <div v-if="errorMessage" class="error-banner">
        {{ errorMessage }}
      </div>

      <div v-if="isLoading" class="status-msg">
        <p>Loading records...</p>
      </div>

      <div v-else-if="theses.length === 0" class="status-msg">
        <p>No thesis files found. Upload one to get started!</p>
      </div>

      <div v-else class="table-wrapper">
        <table>
          <thead>
            <tr>
              <th>Title</th>
              <th>Author</th>
              <th>Year</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="thesis in theses" :key="thesis.id">
              <td>{{ thesis.title }}</td>
              <td>{{ thesis.author }}</td>
              <td>{{ thesis.year }}</td>
              <td>
                <a :href="thesis.file_path" target="_blank" rel="noopener noreferrer">
                  <button class="view-btn">View PDF</button>
                </a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</template>

<style scoped>
.dashboard-container {
  min-height: 100vh;
  background: linear-gradient(135deg, #003a5c 0%, #004d7a 100%);
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: rgba(0, 58, 92, 0.95);
  padding: 1.5rem 2rem;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
  border-bottom: 4px solid #ffc800;
}

.header h1 {
  margin: 0;
  color: white;
  font-size: 2rem;
  font-family: 'Georgia', serif;
}

.buttons {
  display: flex;
  gap: 1rem;
}

.upload-btn {
  background: linear-gradient(135deg, #ffc800 0%, #ffb300 100%);
  color: #003a5c;
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 6px;
  font-weight: 700;
  cursor: pointer;
  text-transform: uppercase;
  transition: all 0.3s;
}

.logout-btn {
  background: #d32f2f;
  color: white;
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 6px;
  font-weight: 700;
  cursor: pointer;
  text-transform: uppercase;
}

.content {
  padding: 2rem;
}

.status-msg {
  color: white;
  text-align: center;
  font-size: 1.2rem;
  margin-top: 2rem;
}

.error-banner {
  background: #ffebee;
  color: #c62828;
  padding: 1rem;
  border-radius: 8px;
  margin-bottom: 1rem;
  text-align: center;
  font-weight: bold;
}

.table-wrapper {
  background: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

table {
  width: 100%;
  border-collapse: collapse;
}

thead {
  background: linear-gradient(135deg, #003a5c 0%, #004d7a 100%);
}

th {
  color: white;
  padding: 1rem;
  text-align: left;
  font-weight: 600;
  text-transform: uppercase;
  font-size: 0.85rem;
}

td {
  padding: 1rem;
  border-bottom: 1px solid #e0e0e0;
  color: #333;
}

tbody tr:hover {
  background-color: #f9f9f9;
}

.view-btn {
  background: #00796b;
  color: white;
  padding: 0.6rem 1rem;
  border: none;
  border-radius: 4px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s;
}

.view-btn:hover {
  background: #004d40;
}

a {
  text-decoration: none;
}
</style>
