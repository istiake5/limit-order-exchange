<script setup>
import { ref, onMounted, computed } from "vue";
import api from "../api/http";
import Wallet from "../components/Wallet.vue";
import OrderForm from "../components/OrderForm.vue";
import Orders from "../components/Orders.vue";
import Echo from "../plugins/echo";
import { useToast } from "../composables/useToast";

const { addToast } = useToast();

const profile = ref(null);
const orders = ref([]);

// Filters
const filterSymbol = ref("all");
const filterSide = ref("all");
const filterStatus = ref("all");

// Fetch profile
const fetchProfile = async () => {
  profile.value = (await api.get("/profile")).data;
};

// Fetch orders
const fetchOrders = async () => {
  orders.value = (await api.get("/orders?symbol=BTC")).data;
};

// Cancel order
const handleCancel = async (orderId) => {
  try {
    await api.post(`/orders/${orderId}/cancel`);
    fetchOrders();
    fetchProfile();
  } catch (err) {
    addToast(
      "Cancel failed: " + (err.response?.data?.message || err.message),
      "error"
    );
  }
};

// Filtered orders
const filteredOrders = computed(() => {
  return orders.value.filter(
    (o) =>
      (filterSymbol.value === "all" || o.symbol === filterSymbol.value) &&
      (filterSide.value === "all" || o.side === filterSide.value) &&
      (filterStatus.value === "all" ||
        (filterStatus.value === "open" && o.status === 1) ||
        (filterStatus.value === "filled" && o.status === 2) ||
        (filterStatus.value === "cancelled" && o.status === 3))
  );
});

onMounted(async () => {
  await fetchProfile();
  await fetchOrders();

  // Real-time updates
  Echo.private(`user.${profile.value.id}`)
    .listen(".OrderMatched", () => {
      fetchProfile();
      fetchOrders();
      addToast("Your order was matched!", "info");
    })
    .listen(".OrderCancelled", () => {
      fetchOrders();
      addToast("Order cancelled successfully!", "info");
    });
});
</script>

<template>
  <div class="p-4 bg-gray-100 min-h-screen">
    <Wallet :profile="profile" />
    <OrderForm @orderPlaced="fetchOrders" />

    <!-- Filters -->
    <div class="flex gap-2 mb-4">
      <select v-model="filterSymbol" class="border p-1 rounded">
        <option value="all">All Symbols</option>
        <option value="BTC">BTC</option>
        <option value="ETH">ETH</option>
      </select>

      <select v-model="filterSide" class="border p-1 rounded">
        <option value="all">All Sides</option>
        <option value="buy">Buy</option>
        <option value="sell">Sell</option>
      </select>

      <select v-model="filterStatus" class="border p-1 rounded">
        <option value="all">All Status</option>
        <option value="open">Open</option>
        <option value="filled">Filled</option>
        <option value="cancelled">Cancelled</option>
      </select>
    </div>

    <Orders :orders="filteredOrders" @cancelOrder="handleCancel" />

    <!-- Toasts -->
    <Toast />
  </div>
</template>
