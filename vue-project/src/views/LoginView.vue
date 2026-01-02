<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const email = ref('')
const password = ref('')
const loading = ref(false)
const errorMessage = ref('')

const handleLogin = async () => {
  loading.value = true
  errorMessage.value = ''

  // Mock authentication - allow any valid email and password
  if (email.value && password.value && password.value.length >= 6) {
    // Simulate API delay
    await new Promise(resolve => setTimeout(resolve, 1000))
    router.push('/dashboard')
  } else {
    errorMessage.value = 'Please enter a valid email and password (min 6 characters)'
  }
  loading.value = false
}

const handleSignUp = async () => {
  loading.value = true
  errorMessage.value = ''

  if (email.value && password.value && password.value.length >= 6) {
    await new Promise(resolve => setTimeout(resolve, 1000))
    alert("Account created! You can now login.")
    email.value = ''
    password.value = ''
  } else {
    errorMessage.value = 'Please enter a valid email and password (min 6 characters)'
  }
  loading.value = false
}
</script>

<template>
  <div class="login-container">
    <div class="card">
      <h1>Thesis Archive Login</h1>
      <input v-model="email" type="email" placeholder="Email" />
      <input v-model="password" type="password" placeholder="Password (min 6 chars)" />

      <p v-if="errorMessage" class="error-text">{{ errorMessage }}</p>

      <div class="btn-group">
        <button @click="handleLogin" :disabled="loading">
          {{ loading ? 'Loading...' : 'Login' }}
        </button>
        <button @click="handleSignUp" :disabled="loading" class="secondary">
          Sign Up
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background: linear-gradient(135deg, #003a5c 0%, #004d7a 100%);
}
.card {
  background: white;
  padding: 2rem;
  border-radius: 12px;
  width: 100%;
  max-width: 400px;
  text-align: center;
}
input {
  display: block;
  width: 100%;
  padding: 10px;
  margin: 10px 0;
  border: 1px solid #ccc;
  border-radius: 4px;
}
.btn-group {
  display: flex;
  gap: 10px;
  margin-top: 20px;
}
button {
  flex: 1;
  padding: 10px;
  background: #003a5c;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}
button.secondary {
  background: #666;
}
.error-text {
  color: #d32f2f;
  font-size: 0.9rem;
  margin: 10px 0;
}
</style>
