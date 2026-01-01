<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../api/http'

const name = ref('')
const email = ref('')
const password = ref('')
const passwordConfirmation = ref('')
const router = useRouter()

const register = async () => {
  if (password.value !== passwordConfirmation.value) {
    alert('Passwords do not match')
    return
  }

  try {
    await api.post('/register', {
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: passwordConfirmation.value,
    })
    router.push('/login')
  } catch (err) {
    alert('Registration failed: ' + err.response?.data?.message || err.message)
  }
}
</script>

<template>
  <div class="h-screen flex items-center justify-center bg-gray-100">
    <div class="w-96 p-6 bg-white rounded shadow">
      <h1 class="text-2xl font-bold mb-6 text-center">Register</h1>

      <input
        v-model="name"
        placeholder="Full Name"
        class="w-full p-2 border rounded mb-4"
      />
      <input
        v-model="email"
        type="email"
        placeholder="Email"
        class="w-full p-2 border rounded mb-4"
      />
      <input
        v-model="password"
        type="password"
        placeholder="Password"
        class="w-full p-2 border rounded mb-4"
      />
      <input
        v-model="passwordConfirmation"
        type="password"
        placeholder="Confirm Password"
        class="w-full p-2 border rounded mb-4"
      />

      <button
        @click="register"
        class="w-full bg-green-500 hover:bg-green-600 text-white py-2 rounded"
      >
        Register
      </button>

      <p class="mt-4 text-center">
        Already have an account?
        <router-link to="/login" class="text-blue-500 hover:underline">Login</router-link>
      </p>
    </div>
  </div>
</template>
