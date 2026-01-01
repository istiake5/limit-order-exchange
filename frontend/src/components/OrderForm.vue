<script setup>
import { ref, computed } from "vue";
import api from "../api/http";
import { useToast } from "../composables/useToast";

const { addToast } = useToast();
const emit = defineEmits(["orderPlaced"]);

const symbol = ref("BTC");
const side = ref("buy");
const price = ref("");
const amount = ref("");

const volume = computed(() => {
  const p = parseFloat(price.value);
  const a = parseFloat(amount.value);
  return p && a ? (p * a).toFixed(2) : "0.00";
});

const submitOrder = async () => {
  try {
    await api.post("/orders", {
      symbol: symbol.value,
      side: side.value,
      price: parseFloat(price.value),
      amount: parseFloat(amount.value),
    });
    addToast("Order placed successfully!", "success");
    price.value = "";
    amount.value = "";
    emit("orderPlaced");
  } catch (err) {
    addToast(
      "Error placing order: " + (err.response?.data?.message || err.message),
      "error"
    );
  }
};
</script>

<template>
  <div class="p-4 border rounded mb-4 bg-white shadow">
    <h2 class="font-bold mb-2 text-lg">Place Limit Order</h2>

    <div class="flex gap-2 mb-2">
      <select v-model="symbol" class="border rounded p-2 flex-1">
        <option value="BTC">BTC</option>
        <option value="ETH">ETH</option>
      </select>
      <select v-model="side" class="border rounded p-2 flex-1">
        <option value="buy">Buy</option>
        <option value="sell">Sell</option>
      </select>
    </div>

    <input
      v-model="price"
      type="number"
      placeholder="Price"
      class="w-full p-2 border rounded mb-2"
    />
    <input
      v-model="amount"
      type="number"
      placeholder="Amount"
      class="w-full p-2 border rounded mb-2"
    />

    <p class="text-sm text-gray-500 mb-2">Volume: {{ volume }} USD</p>

    <button
      @click="submitOrder"
      class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded"
    >
      Submit
    </button>
  </div>
</template>
