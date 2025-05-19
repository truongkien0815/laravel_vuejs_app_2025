<template>
   <!-- Form thêm sản phẩm -->
    <div class="container">
      <input v-model="newProductName" placeholder="Nhập tên sản phẩm" />
       <input v-model="newProductClass" placeholder="Nhập tên sản phẩm" />
      <button @click="addProduct">Thêm</button>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
// chuyển hướng
import { useRouter } from 'vue-router';

const router = useRouter();
// 

import appcopy from './Appcopy.vue'

const todos = ref([]);
const newProductName = ref("");
const newProductClass = ref("");
const editingId = ref(null);
const editedName = ref("");
const showId = ref();
const showName = ref();

// import { RouterView, RouterLink } from 'vue-router';

// Lấy danh sách sản phẩm từ API
onMounted(() => {
  fetch('./api/user_all')
    .then(response => response.json())
    .then(json => todos.value = json);
});

// Thêm sản phẩm
const addProduct = async () => {
  if (!newProductName.value.trim()) {
    alert("Vui lòng nhập tên sản phẩm!");
    return;
  }

  try {
    const response = await fetch('./api/user_add', {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ 
        name: newProductName.value,
        class: newProductClass.value
       }
     
      ),
    });

    if (response.ok) {
      const newProduct = await response.json();
      todos.value.push(newProduct); // Thêm vào danh sách
      newProductName.value = ""; // Xóa input sau khi thêm
      newProductClass.value = ""; 
    } else {
      alert("Thêm sản phẩm thất bại!");
    }
  } catch (error) {
    console.error("Lỗi khi thêm sản phẩm:", error);
  }
    setTimeout(() => {
        router.push('/');
      });
};

</script>

<style>

</style>