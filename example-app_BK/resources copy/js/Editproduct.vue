<template>
   <div class="container">
      <input v-model="editedName" placeholder="Sửa tên sản phẩm" />
       <input v-model="editclass" placeholder="Nhập tên sản phẩm" />
      <button @click="updateProduct(route.params.id)">edit</button>
    </div>
</template>

<script setup>
import { ref, watchEffect } from "vue";
import { useRoute, useRouter } from "vue-router";

const route = useRoute();
const router = useRouter();
const productId = ref(route.params.id); // Lấy ID từ URL
const editclass = ref();
const editedName = ref(""); // Tên sản phẩm

// 🟢 Hàm lấy thông tin sản phẩm
const fetchProduct = async () => {
  try {
    const response = await fetch(`/api/user_show/${productId.value}`); // Gọi API lấy sản phẩm theo ID
    if (response.ok) {
      const data = await response.json();
      editedName.value = data.name; // Hiển thị tên sản phẩm trong input
      editclass.value = data.class;
    } else {
      console.error("Không tìm thấy sản phẩm");
    }
  } catch (error) {
    console.error("Lỗi khi gọi API:", error);
  }
};

// 🟢 Khi đường dẫn thay đổi, tự động lấy sản phẩm mới
watchEffect(() => {
  if (productId.value) fetchProduct();
});




const updateProduct = async (id) => {
  try {
    const response = await fetch(`/api/user_update/${id}`, {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ name: editedName.value,
        class: editclass.value
       }),
      
    });

    if (response.ok) {
      
      const updatedProduct = todos.value.find(p => p.id === id);
      if (updatedProduct) updatedProduct.name = editedName.value;
 updatedProduct.class = editclass.value;
  
      editingId.value = null; // Ẩn form sửa
      
    } else {
      alert("Cập nhật thất bại!");
    }
  } catch (error) {
    console.error("Lỗi khi cập nhật:", error);
  }
  setTimeout(() => {
        router.push('/');
      });
};
</script>

<style>

</style>