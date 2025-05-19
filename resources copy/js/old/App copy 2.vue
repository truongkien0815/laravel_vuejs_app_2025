<template>
  <div>
    <h1>Danh sách sản phẩm</h1>
    <table border="1">
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Hành động</th>
      </tr>
      <tr v-for="product in todos" :key="product.id">
        <td>{{ product.id }}</td>
        <td v-if="editingId !== product.id">{{ product.name }}</td>
        <td v-else>
          <input v-model="editedName" />
        </td>
        <td>
          <button v-if="editingId !== product.id" @click="editProduct(product)">Sửa</button>
          <button v-else @click="updateProduct(product.id)">Lưu</button>
          <button @click="deleteProduct(product.id)">Xóa</button>
        </td>
      </tr>
    </table>
  </div>
</template>
<script setup>
import { ref, onMounted } from 'vue';

const todos = ref([]);
const editingId = ref(null);
const editedName = ref("");

// Lấy danh sách sản phẩm từ API
onMounted(() => {
  fetch('./api/user_all')
    .then(response => response.json())
    .then(json => todos.value = json);
});

// Bắt đầu sửa sản phẩm
const editProduct = (product) => {
  editingId.value = product.id;
  editedName.value = product.name;
};

// Cập nhật sản phẩm
const updateProduct = async (id) => {
  try {
    const response = await fetch(`./api/user_update/${id}`, {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ name: editedName.value }),
    });

    if (response.ok) {
      // Cập nhật giao diện sau khi sửa
      const updatedProduct = todos.value.find(p => p.id === id);
      if (updatedProduct) updatedProduct.name = editedName.value;

      editingId.value = null; // Ẩn form sửa
    } else {
      alert("Cập nhật thất bại!");
    }
  } catch (error) {
    console.error("Lỗi khi cập nhật:", error);
  }
};

// Xóa sản phẩm
const deleteProduct = async (id) => {
  if (confirm("Bạn có chắc chắn muốn xóa?")) {
    try {
      const response = await fetch(`./api/user_delete/${id}`, { method: 'DELETE' });
      if (response.ok) {
        todos.value = todos.value.filter(product => product.id !== id);
      } else {
        alert("Xóa thất bại!");
      }
    } catch (error) {
      console.error("Lỗi khi xóa:", error);
    }
  }
};
</script>
