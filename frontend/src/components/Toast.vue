<template>
  <div class="fixed top-5 right-5 space-y-2 z-50">
    <div
      v-for="t in toasts"
      :key="t.id"
      :class="[
        'p-3 rounded shadow text-white',
        t.type === 'info'
          ? 'bg-blue-500'
          : t.type === 'success'
          ? 'bg-green-500'
          : 'bg-red-500',
      ]"
    >
      {{ t.message }}
    </div>
  </div>
</template>

<script setup>
import { useToast } from "../composables/useToast";
const { toasts } = useToast();
</script>
