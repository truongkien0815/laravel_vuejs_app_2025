<template>
   <!-- Form thêm sản phẩm -->
    <div class="container">
     <div>
     <div > <label for="">tên sản phẩm</label></div>
       <input v-model="newProductName" placeholder="Nhập tên sản phẩm" />
     </div>
      <div><div><label for="">class sản phẩm</label></div>
         <input v-model="newProductClass" placeholder="Nhập class sản phẩm" /></div>
        <img :src="img_product" alt="Ảnh sản phẩm" v-if="img_product" width="200px"/>
     <input type="file" name="img_product" @change="onFileChange" />
    
      <div>  <div><label for="">chi tiết sản phẩm</label></div>
      <textarea v-model="newProductDetail" placeholder="Nhập mô tả sản phẩm..." cols="40" rows="5"></textarea>
      <!-- <input v-model="newProductDetail" placeholder="Nhập chi tiết sản phẩm" /> -->
      </div>
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
const newProductDetail = ref("");
const editingId = ref(null);
const editedName = ref("");
const showId = ref();
const showName = ref();

const img_product = ref("");
const selectedFile = ref(null); // Lưu file ảnh


// Lấy danh sách sản phẩm từ API
onMounted(() => {
  fetch('./api/user_all')
    .then(response => response.json())
    .then(json => todos.value = json);
});

const onFileChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    selectedFile.value = file; // Lưu file vào biến selectedFile
    img_product.value = URL.createObjectURL(file); // Hiển thị ảnh trước khi upload
  }
};
// Thêm sản phẩm
const addProduct = async () => {
  if (!newProductName.value.trim()) {
    alert("Vui lòng nhập tên sản phẩm!");
    return;
  }
  // 
  if (!selectedFile.value) {
    alert("Vui lòng chọn ảnh");
    // console.error("Chưa chọn file!");
    return;
  }

  // console.log("File đã chọn:", selectedFile.value.name);

  const formData = new FormData();
  formData.append("name", newProductName.value);
  formData.append("class", newProductClass.value);
    formData.append("detail", newProductDetail.value);
  formData.append("image", selectedFile.value); // 🟢 Gửi file ảnh
  // 
 try {
    const response = await fetch(`/api/user_add`, {
      method: "POST", // 🛑 Laravel không hỗ trợ PUT với FormData, nên dùng POST
      body: formData, // ❌ Không dùng JSON.stringify()
    });
  // try {
  //   const response = await fetch('./api/user_add', {
  //     method: "POST",
  //     headers: { "Content-Type": "application/json" },
  //     body: JSON.stringify({ 
  //       name: newProductName.value,
  //       class: newProductClass.value,
  //       detail: newProductDetail.value
  //      }
     
  //     ),
  //   });

    if (response.ok) {
      const newProduct = await response.json();
      todos.value.push(newProduct); // Thêm vào danh sách
      newProductName.value = ""; // Xóa input sau khi thêm
      newProductClass.value = ""; 
      newProductDetail.value =""
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