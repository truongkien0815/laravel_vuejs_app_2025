

<script setup>
import { ref, onMounted } from 'vue';


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
};


const showProduct = async (id) => {
    try {
        const response = await fetch(`./api/user_show/${id}`, { method: 'GET' });
        if (response.ok) {
            const product = await response.json(); // Lấy dữ liệu từ API
            console.log("Sản phẩm:", product); // Debug
            // alert(`Tên sản phẩm: ${product.id}\nGiá: ${product.name}`); // Hiển thị
             showId.value = product.id;
             showName.value = product.name;
        } else {
            alert("Không tìm thấy sản phẩm!");
        }
    } catch (error) {
        console.error("Lỗi khi lấy dữ liệu:", error);
    }
};

// Chỉnh sửa sản phẩm
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



<template>

<div class="container">
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Menu</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
     
       
         <li class="nav-item"><router-link to="/">
         <a class="nav-link" href="">🏠 Trang chủ</a></router-link></li>
        <li class="nav-item"><router-link to="/about">
        <a class="nav-link" href="">ℹ️ Giới thiệu</a></router-link></li>
         <li class="nav-item"><router-link to="/contact">
        <a class="nav-link" href="">Liên hệ</a></router-link></li>
      </ul>
    </div>
  </div>
</nav>
</div>
 <!-- <nav>
      <ul>
        <li><router-link to="/">🏠 Trang chủ</router-link></li>
        <li><router-link to="/about1">ℹ️ Giới thiệu</router-link></li>
      </ul>
    </nav> -->

    <!-- Hiển thị component theo route -->
    <router-view />
<!-- <appcopy/> -->
  <!-- <div class="container"> -->
    
   
    <!-- <h1>Danh sách sản phẩm</h1> -->

    <!-- Form thêm sản phẩm -->
    <!-- <div>
      <input v-model="newProductName" placeholder="Nhập tên sản phẩm" />
       <input v-model="newProductClass" placeholder="Nhập tên sản phẩm" />
      <button @click="addProduct">Thêm</button>
    </div>

    <table border="1">
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Class</th>

        <th>Hành động</th>
      </tr>
      <tr v-for="product in todos" :key="product.id">
        <td>{{ product.id }}</td>
        <td v-if="editingId !== product.id">{{ product.name }}</td>
        
          
          <td v-else>
            <input v-model="editedName" />
          </td>
          <td>{{ product.class }}</td>
        <td>
         
          


  <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Modal 1</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       <input type="text" v-model="showId">
       <input type="text" v-model="showName">
      </div>
      
     
    </div>
  </div>
</div>

<button class="btn btn-primary" data-bs-target="#exampleModalToggle" data-bs-toggle="modal" v-if="editingId !== product.id" @click="showProduct(product.id)"
>detail</button>



          <button v-if="editingId !== product.id" @click="editProduct(product)">Sửa</button>
          <button v-else @click="updateProduct(product.id)">Lưu</button>
          <button @click="deleteProduct(product.id)">Xóa</button>
        </td>
      </tr>
    </table>
  </div> -->




</template>








