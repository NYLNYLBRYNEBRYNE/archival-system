<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../api'; 

const router = useRouter();
const theses = ref([]);
const isLoading = ref(true);
const errorMessage = ref('');

const fetchTheses = async () => {
  isLoading.value = true;
  try {
    const response = await api.get('/archives'); 
    theses.value = response.data; 
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
  <div class="min-h-screen bg-linear-to-br from-[#003a5c] to-[#004d7a]">
    
    <header class="flex justify-between items-center bg-[#003a5c]/95 px-8 py-6 shadow-md border-b-4 border-[#ffc800]">
      <h1 class="text-white text-3xl font-serif m-0">📚 Thesis Archive</h1>
      
      <div class="flex gap-4">
        <button 
          @click="router.push('/upload')" 
          class="bg-linear-to-br from-[#ffc800] to-[#ffb300] text-[#003a5c] px-6 py-3 rounded-md font-bold uppercase hover:opacity-90 transition-all duration-300"
        >
          + Upload New Thesis
        </button>
        
        <button 
          @click="router.push('/login')" 
          class="bg-red-700 text-white px-6 py-3 rounded-md font-bold uppercase hover:bg-red-800 transition-colors"
        >
          Logout
        </button>
      </div>
    </header>

    <main class="p-8">
      <div v-if="errorMessage" class="bg-red-100 text-red-800 p-4 rounded-lg mb-4 text-center font-bold">
        {{ errorMessage }}
      </div>

      <div v-if="isLoading" class="text-white text-center text-xl mt-8">
        <p>Loading records...</p>
      </div>

      <div v-else-if="theses.length === 0" class="text-white text-center text-xl mt-8">
        <p>No thesis files found. Upload one to get started!</p>
      </div>

      <div v-else class="bg-white rounded-lg overflow-hidden shadow-2xl">
        <table class="w-full border-collapse">
          <thead>
            <tr class="bg-linear-to-br from-[#003a5c] to-[#004d7a] text-white text-left uppercase text-sm font-semibold">
              <th class="p-4">Title</th>
              <th class="p-4">Author</th>
              <th class="p-4">Year</th>
              <th class="p-4">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="thesis in theses" :key="thesis.id" class="hover:bg-gray-50 border-b border-gray-200 text-gray-800">
              <td class="p-4">{{ thesis.title }}</td>
              <td class="p-4">{{ thesis.author }}</td>
              <td class="p-4">{{ thesis.year }}</td>
              <td class="p-4">
                <a :href="thesis.file_path" target="_blank" rel="noopener noreferrer">
                  <button class="bg-teal-700 hover:bg-teal-800 text-white px-4 py-2 rounded font-semibold transition-colors duration-300">
                    View PDF
                  </button>
                </a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</template>