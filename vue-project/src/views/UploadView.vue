<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import api from '../api'; // Import the Axios client we created earlier

const router = useRouter();

const title = ref('');
const author = ref('');
const year = ref(new Date().getFullYear());
const file = ref(null);
const isUploading = ref(false);

const handleFileChange = (event) => {
  file.value = event.target.files[0];
};

const handleUpload = async () => {
  if (!file.value) return alert("Please select a PDF file first!");
  if (!title.value || !author.value) return alert("Please fill in all fields.");

  isUploading.value = true;

  // --- NEW LARAVEL LOGIC ---
  // We use FormData to send text fields AND the file in one request
  const formData = new FormData();
  formData.append('title', title.value);
  formData.append('author', author.value);
  formData.append('year', year.value);
  formData.append('file', file.value); // The actual PDF object

  try {
    // Send POST request to Laravel (http://localhost:8000/api/archives)
    const response = await api.post('/archives', formData, {
      headers: {
        'Content-Type': 'multipart/form-data' // Critical for file uploads!
      }
    });

    console.log("Upload success:", response.data);
    alert("Thesis Uploaded Successfully!");
    router.push('/dashboard');

  } catch (err) {
    console.error("Upload failed:", err);
    // Display the error message from Laravel if available
    const msg = err.response?.data?.message || err.message;
    alert("Upload Failed: " + msg);
  } finally {
    isUploading.value = false;
  }
};
</script>

<template>
  <div class="upload-container">
    <div class="card">
      <h2>Upload New Thesis</h2>

      <div class="form-group">
        <label>Thesis Title</label>
        <input v-model="title" type="text" placeholder="Enter thesis title" />
      </div>

      <div class="form-group">
        <label>Author Name</label>
        <input v-model="author" type="text" placeholder="Enter author name" />
      </div>

      <div class="form-group">
        <label>Year</label>
        <input v-model="year" type="number" />
      </div>

      <div class="form-group">
        <label>PDF File</label>
        <input @change="handleFileChange" type="file" accept="application/pdf" />
      </div>

      <div class="btn-group">
        <button @click="handleUpload" :disabled="isUploading" class="submit-btn">
          {{ isUploading ? 'Uploading...' : 'Submit Thesis' }}
        </button>
        <button @click="router.push('/dashboard')" class="cancel-btn">
          Cancel
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Your styles remain exactly the same as before */
.upload-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background: linear-gradient(135deg, #003a5c 0%, #004d7a 100%);
  padding: 1rem;
}

.card {
  background: white;
  padding: 2.5rem;
  border-radius: 12px;
  width: 100%;
  max-width: 500px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

h2 {
  color: #003a5c;
  text-align: center;
  margin-bottom: 2rem;
  font-family: 'Georgia', serif;
}

.form-group {
  margin-bottom: 1.5rem;
}

label {
  display: block;
  margin-bottom: 0.5rem;
  color: #333;
  font-weight: 600;
}

input {
  width: 100%;
  padding: 12px;
  border: 2px solid #e0e0e0;
  border-radius: 6px;
  font-size: 1rem;
  transition: border-color 0.3s;
}

input:focus {
  border-color: #003a5c;
  outline: none;
}

.btn-group {
  display: flex;
  gap: 1rem;
  margin-top: 2rem;
}

button {
  flex: 1;
  padding: 12px;
  border: none;
  border-radius: 6px;
  font-weight: bold;
  cursor: pointer;
  transition: transform 0.2s;
}

.submit-btn {
  background: #ffc800;
  color: #003a5c;
}

.cancel-btn {
  background: #f5f5f5;
  color: #666;
}

button:hover {
  transform: translateY(-2px);
}
</style>