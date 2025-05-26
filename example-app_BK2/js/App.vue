

<script setup>

import { ref, onMounted } from "vue";

import appcopy from "./Appcopy.vue";

// const token2 =  ref(localStorage.getItem('token2'));
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
  fetch("./api/user_all")
    .then((response) => response.json())
    .then((json) => (todos.value = json));
});

// Thêm sản phẩm
const addProduct = async () => {
  if (!newProductName.value.trim()) {
    alert("Vui lòng nhập tên sản phẩm!");
    return;
  }

  try {
    const response = await fetch("./api/user_add", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        name: newProductName.value,
        class: newProductClass.value,
      }),
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
    const response = await fetch(`./api/user_show/${id}`, { method: "GET" });
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
      const updatedProduct = todos.value.find((p) => p.id === id);
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
      const response = await fetch(`./api/user_delete/${id}`, {
        method: "DELETE",
      });
      if (response.ok) {
        todos.value = todos.value.filter((product) => product.id !== id);
      } else {
        alert("Xóa thất bại!");
      }
    } catch (error) {
      console.error("Lỗi khi xóa:", error);
    }
  }
};

import { useRoute, useRouter } from "vue-router";
import { useToast } from "vue-toastification";
const toast = useToast()

const router = useRouter();

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
            <li class="nav-item">
              <router-link to="/contact">
                <a class="nav-link" href="">Sản phẩm</a></router-link
              >
            </li>
            <li class="nav-item">
              <router-link to="/login">
                <a class="nav-link" href="">Login</a></router-link
              >
            </li>

            <button style="" @click="logout">logout</button>
            <!-- <button v-if="token2">{{ token2 }}</button> -->

            <!-- <button>{{ token2}}</button> -->

            <!-- <li class="nav-item"><router-link to="/logout">
        <a class="nav-link" href="">Logout</a></router-link></li> -->
          </ul>
        </div>
      </div>
    </nav>
  </div>

  <!-- Hiển thị component theo route -->
  <router-view />
  <!-- <appcopy/> -->

 <!-- Footer -->
<footer class="text-center text-lg-start bg-body-tertiary text-muted" style="margin-top:20px">
  <!-- Section: Social media -->
  <section class="d-flex justify-content-center justify-content-lg-between p-4">
 

   
  </section>
  <!-- Section: Social media -->

  <!-- Section: Links  -->
  <section class="">
    <div class="container text-center text-md-start mt-5">
      <!-- Grid row -->
      <div class="row mt-3">
        <!-- Grid column -->
        <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
          <!-- Content -->
          <h6 class="text-uppercase fw-bold mb-4">
            <i class="fas fa-gem me-3"></i>Info
          </h6>
           <div class="footer-add">
        <div> Trương Trung Kiên</div>
<div>Địa chỉ:  Tp Hồ Chí Minh.</div>
<div>Tel: 0815 686 825</div>
<div>Email: truongkien0815@gmail.com</div>
<div>Website: http://kienweb.shop</div>
      </div>
        </div>
        <!-- Grid column -->

        <!-- Grid column -->
        <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
          <!-- Links -->
          <h6 class="text-uppercase fw-bold mb-4">
            Products
          </h6>
       
        
          <p>
            <a href="#!" class="text-reset">Vue</a>
          </p>
          <p>
            <a href="#!" class="text-reset">Laravel</a>
          </p>
        </div>
        <!-- Grid column -->

        <!-- Grid column -->
        <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
          <!-- Links -->
          <h6 class="text-uppercase fw-bold mb-4">
            Useful links
          </h6>
          <p>
            <a href="#!" class="text-reset">Pricing</a>
          </p>
          <p>
            <a href="#!" class="text-reset">Settings</a>
          </p>
          <p>
            <a href="#!" class="text-reset">Orders</a>
          </p>
          <p>
            <a href="#!" class="text-reset">Help</a>
          </p>
        </div>
        <!-- Grid column -->

        <!-- Grid column -->
        <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
          <!-- Links -->
          <h6 class="text-uppercase fw-bold mb-4">Contact</h6>
          <p><i class="fas fa-home me-3"></i>  Tp Hồ Chí Minh</p>
          <p>
            <i class="fas fa-envelope me-3"></i>
            truongkien0815@gmail.com
          </p>
          <p><i class="fas fa-phone me-3"></i> 0815 686 825</p>
       
        </div>
        <!-- Grid column -->
      </div>
      <!-- Grid row -->
    </div>
  </section>
  <!-- Section: Links  -->


</footer>
<!-- Footer -->
</template>
<style>
.footer-add{
  text-align: left;
}
</style>








