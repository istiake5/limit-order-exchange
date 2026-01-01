<script setup>
import { ref } from 'vue'
import api from '../api/http'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const email = ref('')
const password = ref('')
const error = ref('')

const login = async () => {
  try {
    // 1️⃣ Get CSRF cookie from Laravel Sanctum
    await axios.get('http://localhost:8000/sanctum/csrf-cookie', { withCredentials: true })

    // 2️⃣ Login request (must also use withCredentials!)
    const response = await axios.post(
      'http://localhost:8000/api/login',
      {
        email: email.value,
        password: password.value
      },
      { withCredentials: true }
    )

    // 3️⃣ Save user info in localStorage or state
    localStorage.setItem('user', JSON.stringify(response.data.user))

    // 4️⃣ Redirect to dashboard
    router.push('/dashboard')
  } catch (err) {
    error.value = err.response?.data?.message || err.message
  }
}

</script>

<template>
  <div class="max-w-md mx-auto mt-20 p-6 bg-white rounded shadow">
    <h2 class="text-xl font-bold mb-4">Login</h2>

    <div class="mb-2">
      <input
        v-model="email"
        type="email"
        placeholder="Email"
        class="w-full p-2 border rounded"
      />
    </div>

    <div class="mb-2">
      <input
        v-model="password"
        type="password"
        placeholder="Password"
        class="w-full p-2 border rounded"
      />
    </div>

    <div v-if="error" class="text-red-500 mb-2">{{ error }}</div>

    <button
      @click="login"
      class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded"
    >
      Login
    </button>
  </div>
</template>
