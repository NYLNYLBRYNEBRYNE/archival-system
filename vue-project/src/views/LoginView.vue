<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../api' // Uses your existing axios instance

const router = useRouter()
const isRegisterMode = ref(false) // State to toggle forms

const name = ref('')
const email = ref('')
const password = ref('')
const loading = ref(false)
const errorMessage = ref('')

// Toggle between Login and Register modes
const toggleMode = () => {
  isRegisterMode.value = !isRegisterMode.value
  errorMessage.value = ''
  // Clear inputs
  name.value = ''
  email.value = ''
  password.value = ''
}

const handleAuth = async () => {
  loading.value = true
  errorMessage.value = ''

  try {
    if (isRegisterMode.value) {
      // --- REGISTER ---
      await api.post('/register', {
        name: name.value,
        email: email.value,
        password: password.value
      })
      
      alert("Account created successfully! Please log in.")
      toggleMode() // Switch back to login view
      
    } else {
      // --- LOGIN ---
      const response = await api.post('/login', {
        email: email.value,
        password: password.value
      })
      
      // Optional: Store user info in localStorage if you want to remember them
      localStorage.setItem('user', JSON.stringify(response.data.user))
      
      console.log("Login success:", response.data)
      router.push('/dashboard')
    }
  } catch (err) {
    console.error(err)
    // Handle specific error messages from Laravel
    errorMessage.value = err.response?.data?.message || "An error occurred. Please try again."
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="login-container">
    <div class="card">
      <h1>{{ isRegisterMode ? 'Create Account' : 'Thesis Archive Login' }}</h1>
      
      <div v-if="isRegisterMode" class="form-group">
        <input v-model="name" type="text" placeholder="Full Name" />
      </div>

      <div class="form-group">
        <input v-model="email" type="email" placeholder="Email" />
      </div>

      <div class="form-group">
        <input v-model="password" type="password" placeholder="Password (min 6 chars)" />
      </div>

      <p v-if="errorMessage" class="error-text">{{ errorMessage }}</p>

      <div class="btn-group">
        <button @click="handleAuth" :disabled="loading" class="primary-btn">
          {{ loading ? 'Processing...' : (isRegisterMode ? 'Sign Up' : 'Login') }}
        </button>
      </div>

      <div class="toggle-link">
        <p v-if="!isRegisterMode">
          Don't have an account? 
          <span @click="toggleMode">Sign Up</span>
        </p>
        <p v-else>
          Already have an account? 
          <span @click="toggleMode">Login</span>
        </p>
      </div>
    </div>
  </div>
</template>

<style scoped>
.login-container {
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
  max-width: 400px;
  text-align: center;
  box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

h1 {
  margin-bottom: 1.5rem;
  color: #003a5c;
  font-family: 'Georgia', serif;
  font-size: 1.8rem;
}

.form-group {
  margin-bottom: 1rem;
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

.primary-btn {
  width: 100%;
  padding: 12px;
  background: #003a5c;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: bold;
  font-size: 1rem;
  margin-top: 0.5rem;
  transition: transform 0.2s, background 0.3s;
}

.primary-btn:hover {
  background: #005080;
  transform: translateY(-2px);
}

.primary-btn:disabled {
  background: #ccc;
  cursor: not-allowed;
  transform: none;
}

.error-text {
  color: #d32f2f;
  font-size: 0.9rem;
  margin: 10px 0;
  background: #ffebee;
  padding: 0.75rem;
  border-radius: 6px;
}

.toggle-link {
  margin-top: 1.5rem;
  font-size: 0.9rem;
  color: #666;
}

.toggle-link span {
  color: #ffc800; /* Uses your theme's accent color */
  font-weight: bold;
  cursor: pointer;
  text-decoration: underline;
  margin-left: 5px;
}

.toggle-link span:hover {
  color: #e6b400;
}
</style>