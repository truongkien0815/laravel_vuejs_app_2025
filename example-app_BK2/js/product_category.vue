<template>
  <div>
    <!-- <input type="text" v-model="categoryId" placeholder="Nhập Category ID" /> -->

    <ul style="display:flex">
      <li class="li_cate" v-for="product in products" :key="product.id">
        <div class="btn"  :class="{
      'btn-primary': activeCategoryId !== product.id,
      'btn-success': activeCategoryId === product.id
    }"
     @click="fetchProductsByCategory(product.id)">
          {{ product.name }}
        </div>
      </li>
      
    </ul>

    <!-- <h3> sản phẩm theo loại </h3> -->
     <li v-if="find">{{ find }}</li>
       <div v-if="loading" class="spinner-border" role="status">
  <span class="visually-hidden">Loading...</span>
</div>

 <div class="row">

    <div class="card" v-for="product in products_all" :key="product.id" >
  <img :src="product.image" class="card-img-top" alt="">
  <div class="card-body">
    <p class="card-text">{{ product.name}}</p>
     <router-link :to="'/' + product.id"> <div class="btn btn-warning">chi tiết</div></router-link>
       <button
            class="btn btn-primary"
            @click="addToCart(product)"
            >
            Thêm vào giỏ hàng
          </button>
  </div>
</div>

 </div>
    <ul>
      <li v-for="item in productsall" :key="item.id">
        {{ item.name }}
      </li>
    </ul>
  </div>
</template>


<script setup>
// kế thừa addtoCart từ file Contact me
defineProps({
  addToCart: Function
});
// 
import { onMounted, ref, watch } from "vue";
import { useRoute } from "vue-router";



const products = ref([]);
const productsall = ref([]);
const loading = ref(false);


onMounted(async() => {
   const response = await fetch(`/api/category`);
    const data = await response.json();
    products.value = data;

 
    
});

const products_all = ref([]);
onMounted(() => {
  fetch('/api/product_all')
    .then(response => response.json())
    .then(json => products_all.value = json);
});





const categoryId = ref('')
const find = ref();
const activeCategoryId = ref(null);
// Theo dõi thay đổi categoryId → tự động fetch lại sản phẩm
const fetchProductsByCategory = async (categoryId) => {
   activeCategoryId.value = categoryId; 
  if (!categoryId) {
    productsall.value = [];
    return;
  }

  loading.value = true; // Bắt đầu loading
  find.value = "";       // Xóa thông báo cũ

  try {
    const response = await fetch(`/api/cate_product?category_id=${categoryId}`);
    const data = await response.json();

    if (!data.users || data.users.length === 0) {
      find.value = "Không có sản phẩm.";
      products_all.value = [];
    } else {
      products_all.value = data.users;
    }
  } catch (error) {
    console.error('Lỗi khi fetch dữ liệu:', error);
    find.value = "Lỗi khi tải dữ liệu.";
  } finally {
    loading.value = false; // Kết thúc loading
  }
};



</script>

<style>
.li_cate{
  list-style: none;
  padding: 10px;
  width: 30%;
}
.li_cate .btn{
 font-weight: 600;
  width: 100%;
  border-radius: 15px;
}
</style>