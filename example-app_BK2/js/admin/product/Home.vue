

<script setup>
import { ref, onMounted } from "vue";


// Dấu @ thường là alias cho thư mục resources/js trong các dự án Laravel + Vite hoặc Webpack (tuỳ vào cấu hình).
import appcopy from "@/Appcopy.vue";
import { useToast } from "vue-toastification";
const toast = useToast();


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
              toast.success("Đã xóa thành công!", {
          toastClassName: "custom-delete-toast",
        });

        // alert("Xóa thành công!");
      } else {
        toast.error("Xóa thất bại! Kiểm tra lại quyền truy cập.");
      }
    } catch (error) {
      console.error("Lỗi khi xóa:", error);
    }
  }
};



const searchFlights = async () => {
  if (keyword.value.trim() === "") {
    fetch("/api/product_all")
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


   
    </div>
    
    <table border="1" style="width: 100%">
      <tr>
        <th style="width: 5%">ID</th>
        <th style="width: 20%">Name</th>
        <th style="width: 20%">Slug</th>
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
.card-text{
  height: 40px;
}
.custom-delete-toast {
  background-color: #e74c3c !important; /* đỏ */
  color: white;
}

</style>







