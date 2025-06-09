


<style>
/* Mở dropdown khi hover */
.nav-item.dropdown:hover .dropdown-menu {
  display: block;
}
.nav-item.dropdown:hover .dropdown-menu {
  display: block;
  margin-top: 0; /* tránh bị nhảy dropdown */
}

</style>
<script setup>

import { ref, onMounted } from "vue";



// const token2 =  ref(localStorage.getItem('token2'));


// import { RouterView, RouterLink } from 'vue-router';











import { useRoute, useRouter } from "vue-router";
import { useToast } from "vue-toastification";
const toast = useToast()



const router = useRouter();
 const token2 = ref();
 token2.value = localStorage.getItem("token2");
const logout = async () => {
  try {
    const token = localStorage.getItem("token");
    const token2 = localStorage.getItem("token2");
    if (!token) {
      console.log("Không có token để đăng xuất!");
      console.log(localStorage.getItem("token"));
      toast("trạng thái đã đăng xuất");
      
      // return;
      // router.push('/login');
    }

    await axios.post(
      "/api/logout",
      {},
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      }
    );

    localStorage.removeItem("token"); // Xóa token khỏi localStorage
    localStorage.removeItem("token2");
    router.push("/login"); // Chuyển hướng về trang đăng nhập

    console.log("Đăng xuất thành công!");
  } catch (error) {
    console.error("Lỗi đăng xuất:");
  }
};
const products_menu = ref([]);
onMounted(async() => {
   const response = await fetch(`/api/category`);
    const data = await response.json();
    products_menu.value = data;

 
    
});

</script>

<template>
   <div class="container">
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">Menu</a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <router-link to="/">
                <a class="nav-link" href="">🏠 Trang chủ</a></router-link
              >
            </li>
             <li class="nav-item">
              <router-link to="/category">
                <a class="nav-link" href="">Danh mục</a></router-link
              >
            </li>
            <li class="nav-item">
              <router-link to="/about">
                <a class="nav-link" href="">ℹ️ Giới thiệu</a></router-link
              >
            </li>

            <!-- <li class="nav-item">
              <router-link to="/contact">
                <a class="nav-link" href="">Sản phẩm</a></router-link
              >
            </li> -->

             <li class="nav-item dropdown">
         
             <router-link to="/contact" class="nav-link dropdown-toggle"  aria-expanded="false">
               Sản phẩm</router-link>
      
          <ul class="dropdown-menu">
        

        
         <li v-for="item in products_menu" :key="item.id">
       <router-link :to="'/about2/' + item.id" class="dropdown-item">
             {{ item.name }}</router-link
              >

      </li>
            <!-- <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li> -->
          </ul>
        </li>
        
            <li class="nav-item">
              <router-link to="/login">
                <a class="nav-link" href="">Login</a></router-link
              >
            </li>

            <button style="" @click="logout">logout</button>
            <button v-if="token2">{{ token2 }}</button> 

            <!-- <button>{{ token2}}</button> -->

            <!-- <li class="nav-item"><router-link to="/logout">
        <a class="nav-link" href="">Logout</a></router-link></li> -->
          </ul>
        </div>
      </div>
    </nav>
  </div>
</template>