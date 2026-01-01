<template>
  <div class="mb-4 p-4 bg-white rounded shadow">
    <h2 class="font-bold mb-2 text-lg">Wallet</h2>
    <p>USD Balance: {{ profile?.balance?.toFixed(2) || 0 }}</p>
    <div class="mt-2">
      <h3 class="font-semibold">Assets:</h3>
      <ul>
        <li v-for="a in profile?.assets || []" :key="a.symbol">
          {{ a.symbol }}: {{ a.amount.toFixed(8) }} (Locked:
          {{ a.locked_amount.toFixed(8) }})
        </li>
      </ul>
    </div>
  </div>
</template>
  
  <script setup>
defineProps({
  profile: Object,
});
</script>
  