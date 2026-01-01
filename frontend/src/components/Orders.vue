<script setup>
defineProps({ orders: Array });
const emit = defineEmits(["cancelOrder"]);
</script>

<template>
  <div class="p-4 bg-white rounded shadow">
    <h2 class="font-bold text-lg mb-2">Orders</h2>
    <table class="w-full text-left border-collapse border">
      <thead>
        <tr class="border-b">
          <th class="p-2 border-b">Symbol</th>
          <th class="p-2 border-b">Side</th>
          <th class="p-2 border-b">Price</th>
          <th class="p-2 border-b">Amount</th>
          <th class="p-2 border-b">Status</th>
          <th class="p-2 border-b">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="order in orders" :key="order.id" class="border-b">
          <td class="p-2">{{ order.symbol }}</td>
          <td class="p-2">{{ order.side }}</td>
          <td class="p-2">{{ order.price }}</td>
          <td class="p-2">{{ order.amount }}</td>
          <td class="p-2">
            <span v-if="order.status === 1">Open</span>
            <span v-else-if="order.status === 2">Filled</span>
            <span v-else>Cancelled</span>
          </td>
          <td class="p-2">
            <button
              v-if="order.status === 1"
              @click="$emit('cancelOrder', order.id)"
              class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded"
            >
              Cancel
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
