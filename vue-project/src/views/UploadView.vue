<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { supabase } from '../supabase';

const router = useRouter();

const title = ref('');
const author = ref('');
const year = ref(new Date().getFullYear());
const file = ref(null);
const isUploading = ref(false);

const handleFileChange = (event) => {
  file.value = event.target.files[0];
  console.log("File selected:", file.value); // Debug log
};

const handleUpload = async () => {
  if (!file.value) return alert("Please select a PDF file first!");
  if (!title.value || !author.value) return alert("Please fill in all fields.");

  isUploading.value = true;
  console.log("Starting upload process...");

  try {
    // --- STEP 1: UPLOAD TO STORAGE ---
    const fileName = `${Date.now()}_${file.value.name.replace(/\s+/g, '_')}`; // Clean filename
    console.log("Attempting to upload file:", fileName);

    const { data: uploadData, error: uploadError } = await supabase.storage
      .from('thesis-files') // Make sure this Bucket exists in Supabase!
      .upload(fileName, file.value);

    if (uploadError) {
      console.error("Storage Error:", uploadError);
      throw new Error("Storage Upload Failed: " + uploadError.message);
    }
    console.log("File uploaded successfully:", uploadData);

    // --- STEP 2: GET PUBLIC URL ---
    const { data: urlData } = supabase.storage
      .from('thesis-files')
      .getPublicUrl(fileName);

    const publicUrl = urlData.publicUrl;
    console.log("Public URL generated:", publicUrl);

    // --- STEP 3: SAVE TO DATABASE ---
    console.log("Saving metadata to database...");
    const { error: dbError } = await supabase
      .from('archives') // Make sure this Table exists!
      .insert([
        {
          title: title.value,
          author: author.value,
          year: year.value,
          file_url: publicUrl
        }
      ]);

    if (dbError) {
      console.error("Database Error:", dbError);
      throw new Error("Database Insert Failed: " + dbError.message);
    }

    console.log("Success! Redirecting...");
    alert("Thesis Uploaded Successfully!");
    router.push('/dashboard');

  } catch (err) {
    console.error("CRITICAL FAILURE:", err);
    alert(err.message);
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
