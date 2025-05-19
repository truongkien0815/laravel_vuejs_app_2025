<script setup>
import { ref, onMounted } from 'vue';

const cart = ref([]);

onMounted(() => {
  const savedCart = JSON.parse(localStorage.getItem("cart"));
  if (savedCart) {
    cart.value = savedCart;
  }
});
</script>

<template>
  <div>
    <h2>Giỏ hàng của bạn</h2>
    <ul>
      <li v-for="(item, index) in cart" :key="index">
        {{ item.name }} - {{ item.price }}₫
      </li>
    </ul>
  </div>
</template>
