

<script setup>
import { ref, onMounted } from "vue";

import appcopy from "./Appcopy.vue";

const cart = ref([]); // Giỏ hàng

const todos = ref([]);
const categories = ref([]);

const newProductName = ref("");
const newProductClass = ref("");
const editingId = ref(null);
const editedName = ref("");
const showId = ref();
const showName = ref();
const keyword = ref("");

const currentPage = ref(1);
const lastPage = ref(1);
const prevPage = ref(null);
const nextPage = ref(null);

// import { RouterView, RouterLink } from 'vue-router';

// Lấy danh sách sản phẩm từ API

// không phân trang
// onMounted(() => {
//   fetch('./api/user_all')
//     .then(response => response.json())
//     .then(json => todos.value = json);
// });

// fetch('https://graph.facebook.com/v22.0/954896870165559?fields=id,name&access_token=YOUR_ACCESS_TOKEN')

// 🟢 Lấy danh sách chuyến bay theo trang
const fetchFlights = async (page = 1) => {
  try {
    const response = await fetch(`/api/flights?page=${page}&per_page=9`);
    const data = await response.json();

    todos.value = data.data;
     categories.value = data.categories;

    currentPage.value = data.current_page;
    lastPage.value = data.last_page;
    prevPage.value = data.prev_page_url;
    nextPage.value = data.next_page_url;
  } catch (error) {
    console.error("Lỗi khi tải dữ liệu:", error);
  }
};

// 🟢 Gọi API khi component được mount
onMounted(() => fetchFlights());

// 


// Khôi phục giỏ hàng từ localStorage khi trang được load
onMounted(() => {
  const savedCart = JSON.parse(localStorage.getItem("cart"));
  if (savedCart) {
    cart.value = savedCart;
  }
});

// Thêm sản phẩm vào giỏ hàng
const addToCart = (product) => {
  const existingProduct = cart.value.find((item) => item.id === product.id);
  if (existingProduct) {
    existingProduct.quantity++;
  } else {
    cart.value.push({ ...product, quantity: 1 });
  }

  // Lưu giỏ hàng vào localStorage
  localStorage.setItem("cart", JSON.stringify(cart.value));

  alert("Đã thêm sản phẩm vào giỏ hàng!");
};

// Xóa sản phẩm khỏi giỏ hàng
const removeFromCart = (productId) => {
  cart.value = cart.value.filter((item) => item.id !== productId);
  localStorage.setItem("cart", JSON.stringify(cart.value));
};

// Chỉnh sửa số lượng sản phẩm trong giỏ hàng
const updateQuantity = (productId, newQuantity) => {
  const product = cart.value.find((item) => item.id === productId);
  if (product && newQuantity > 0) {
    product.quantity = newQuantity; // Cập nhật số lượng
  }

  // Lưu giỏ hàng vào localStorage sau khi chỉnh sửa
  localStorage.setItem("cart", JSON.stringify(cart.value));

  alert(`Số lượng sản phẩm đã được cập nhật thành ${newQuantity}`);
};

// Hiển thị giỏ hàng
const showCart = () => {
  if (cart.value.length > 0) {
    alert("Giỏ hàng:\n" + cart.value.map(item => `${item.name} x${item.quantity}`).join("\n"));
  } else {
    alert("Giỏ hàng của bạn đang trống.");
  }
};
// 


// Thêm sản phẩm
const addProduct = async () => {
  if (!newProductName.value.trim()) {
    alert("Vui lòng nhập tên sản phẩm!");
    return;
  }

  try {
    const response = await fetch("/api/user_add", {
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
    const response = await fetch(`/api/user_show/${id}`, { method: "GET" });
    if (response.ok) {
      const product = await response.json(); // Lấy dữ liệu từ API
      console.log("Sản phẩm:", product); // Debug
      // alert(`Tên sản phẩm: ${product.id}\nGiá: ${product.name}`); // Hiển thị
      showId.value = product.flight.id;
      showName.value = product.flight.name;
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
    const response = await fetch(`/api/user_update/${id}`, {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        name: editedName.value,
        class: "editedName.value",
      }),
    });

    if (response.ok) {
      const updatedProduct = todos.value.find((p) => p.id === id);
      if (updatedProduct) updatedProduct.name = editedName.value;
      updatedProduct.class = "editedName.value";

      editingId.value = null; // Ẩn form sửa
    } else {
      alert("Cập nhật thất bại!");
    }
  } catch (error) {
    console.error("Lỗi khi cập nhật:", error);
  }
};

// Xóa sản phẩm
// const deleteProduct = async (id) => {
//   if (confirm("Bạn có chắc chắn muốn xóa?")) {
//     try {
//       const response = await fetch(`./api/user_delete/${id}`, { method: 'DELETE' });
//       if (response.ok) {
//         todos.value = todos.value.filter(product => product.id !== id);
//       } else {
//         alert("Xóa thất bại!");
//       }
//     } catch (error) {
//       console.error("Lỗi khi xóa:", error);
//     }
//   }
// };

const deleteProduct = async (id) => {
  if (confirm("Bạn có chắc chắn muốn xóa?")) {
    try {
      const token = localStorage.getItem("token");
      const response = await fetch(`/api/user_delete/${id}`, {
        method: "DELETE",
        headers: {
          Authorization: `Bearer ${token}`,
          "Content-Type": "application/json",
        },
      });
      if (response.ok) {
        todos.value = todos.value.filter((product) => product.id !== id);
        alert("Xóa thành công!");
      } else {
        alert("Xóa thất bại! Kiểm tra lại quyền truy cập.");
      }
    } catch (error) {
      console.error("Lỗi khi xóa:", error);
    }
  }
};

// const searchProduct = async (id) => {
//     try {
//         const response = await fetch(`./api/user_show/${id}`, { method: 'GET' });
//         if (response.ok) {
//             const product = await response.json(); // Lấy dữ liệu từ API
//             console.log("Sản phẩm:", product); // Debug
//             // alert(`Tên sản phẩm: ${product.id}\nGiá: ${product.name}`); // Hiển thị
//              showId.value = product.id;
//              showName.value = product.name;
//         } else {
//             alert("Không tìm thấy sản phẩm!");
//         }
//     } catch (error) {
//         console.error("Lỗi khi lấy dữ liệu:", error);
//     }
// };

const searchFlights = async () => {
  if (keyword.value.trim() === "") {
    fetch("/api/user_all")
      .then((response) => response.json())
      .then((json) => (todos.value = json));
    return;
  }

  try {
    const response = await fetch(`/api/search?keyword=${keyword.value}`);
    const data = await response.json();

    if (response.ok) {
      todos.value = data; // Hiển thị danh sách kết quả
    } else {
      flights.value = [];
      message.value = data.message; // Hiển thị thông báo lỗi
    }
  } catch (error) {
    console.log("Lỗi API:");
  }
};
</script>



<template>
  <!-- Hiển thị component theo route -->
  <router-view />
  <!-- <appcopy/> -->
  <div class="container">
    <h1>Danh sách sản phẩm</h1>

    <!-- Form thêm sản phẩm -->

    <div class="ul_header pb-2 pt-2">
      <router-link to="/addproduct" class="addproduct">
        <div class="btn btn-primary">thêm</div></router-link
      >

      <input
        v-model="keyword"
        :id="'search'"
        placeholder="Nhập từ khóa..."
        @input="searchFlights"
      />

<!--  -->

<button class="btn btn-danger" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">Giỏ hàng</button>

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasRightLabel">Giỏ hàng</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <div class="cart" style="display:inline-grid">
      <!-- <button class="btn btn-info" @click="showCart">Xem giỏ hàng</button>
      <div v-if="cart.length > 0">
        <h3>Giỏ hàng:</h3>
        <ul>
          <li v-for="item in cart" :key="item.id">
            {{ item.name }} x{{ item.quantity }}
            <button @click="removeFromCart(item.id)" class="btn btn-danger">Xóa</button>
          </li>
        </ul>
      </div>
      <div v-else>
        <p>Giỏ hàng trống!</p>
      </div> -->

        <div class="cart-container">
    <!-- Danh sách giỏ hàng -->
    <div v-if="cart.length > 0" class="cart-items">
      <h3>Giỏ hàng của bạn</h3>
      <ul>
        <li v-for="item in cart" :key="item.id" class="cart-item">
          <span>{{ item.name }} - {{ item.price }} VNĐ</span>
          <div class="quantity">
            <!-- Chỉnh sửa số lượng sản phẩm -->
            <input type="number" v-model="item.quantity" @change="updateQuantity(item.id, item.quantity)" min="1" />
          </div>
          <button @click="removeFromCart(item.id)">Xóa</button>
        </li>
      </ul>
    </div>

    <!-- Giỏ hàng trống -->
    <div v-else class="empty-cart">
      <p>Giỏ hàng của bạn đang trống.</p>
    </div>
    </div>
    
    </div>
  </div>
</div>
        <!-- Giỏ hàng -->
   
    </div>
      Loại sản phẩm:
     <td v-for="category in categories" :key="category.id">
      {{category.name}} ,
      </td>
    <table border="1" style="width: 100%">
      <tr>
        <th style="width: 5%">ID</th>
        <th style="width: 20%">Name</th>
        <th style="width: 20%">Class</th>
        <th style="width: 20%">Image</th>
        <th style="width: 30%">Hành động</th>
      </tr>
      
      <tr v-for="product in todos" :key="product.id">

  

        <td>{{ product.id }}

             
        </td>
        <td v-if="editingId !== product.id">{{ product.name }}</td>
        <td v-else>
          <input v-model="editedName" />
        </td>
        <td>{{ product.class }}</td>
        <td>
          <img
            :src="product.image"
            alt=""
            height="150px"
            style="max-width: 150px"
          />
        </td>
        <td>
          <div
            class="modal fade"
            id="exampleModalToggle"
            aria-hidden="true"
            aria-labelledby="exampleModalToggleLabel"
            tabindex="-1"
          >
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="exampleModalToggleLabel">
                    Modal 1
                  </h1>
                  <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                  ></button>
                </div>
                <div class="modal-body">
                  <input type="text" v-model="showId" />
                  <input type="text" v-model="showName" />
                </div>
              </div>
            </div>
          </div>

          <button
            class="btn btn-primary"
            data-bs-target="#exampleModalToggle"
            data-bs-toggle="modal"
            v-if="editingId !== product.id"
            @click="showProduct(product.id)"
          >
            detail
          </button>

          <!-- <button v-if="editingId !== product.id" @click="editProduct(product)">Sửa</button> -->
          <button v-else @click="updateProduct(product.id)">Lưu</button>
          <button class="btn btn-danger" @click="deleteProduct(product.id)">Xóa</button>
        
          <router-link :to="'/editproduct/' + product.id"> <div class="btn btn-warning">Edit</div></router-link>
        </td>
      
      </tr>
    </table>
    <div>
      <button v-if="prevPage" @click="fetchFlights(currentPage - 1)">
        ❮ Trước
      </button>

      <button
        v-for="page in lastPage"
        :key="page"
        @click="fetchFlights(page)"
        :class="{ active: currentPage === page }"
      >
        {{ page }}
      </button>

      <button v-if="nextPage" @click="fetchFlights(currentPage + 1)">
        Sau ❯
      </button>
    </div>

   

  </div>
  
</template>

<style>
.active {
  font-weight: bold;
  color: red;
}
#search {
  color: black;
}
.addproduct {
  margin: 30px;
}

.cart {
 
  margin-top: 20px;
}
.cart button {
  margin-left: 10px;
}
</style>







