<template>
  <div>
    <!-- <input type="text" v-model="categoryId" placeholder="Nhập Category ID" /> -->

    <ul style="display:flex">
     

     
    

      <li class="li_cate" v-for="product in products" :key="product.id">
        <div class="btn btn-primary" @click="fetchProductsByCategory(product.id)">
          {{ product.name }}
        </div>
      </li>
      
    </ul>

    <h3>Kết quả sản phẩm theo category:</h3>
     <li v-if="find">{{ find }}</li>
       <div v-if="loading" class="spinner-border" role="status">
  <span class="visually-hidden">Loading...</span>
</div>
    <ul>
      <li v-for="item in productsall" :key="item.id">
        {{ item.name }}
      </li>
    </ul>
  </div>
</template>


<script setup>
import { onMounted, ref, watch } from "vue";
import { useRoute } from "vue-router";



const products = ref([]);
const productsall = ref([]);
const loading = ref(false);


onMounted(async() => {
   const response = await fetch(`/api/category`);
    const data = await response.json();

    products.value = data;
    

 console.log(data)
 
    
});




// const categoryId = ref(); // hoặc từ dropdown, checkbox...

// fetch(`/api/cate_product?category_id=${categoryId}`)
//   .then(res => res.json())
//   .then(data => {
//     console.log(data.users);      
   
//   });

const categoryId = ref('')
const find = ref();

// Theo dõi thay đổi categoryId → tự động fetch lại sản phẩm
const fetchProductsByCategory = async (newVal) => {
  if (!newVal) {
    productsall.value = [];
    return;
  }

  loading.value = true; // Bắt đầu loading
  find.value = "";       // Xóa thông báo cũ

  try {
    const response = await fetch(`/api/cate_product?category_id=${newVal}`);
    const data = await response.json();

    if (!data.users || data.users.length === 0) {
      find.value = "Không có sản phẩm.";
      productsall.value = [];
    } else {
      productsall.value = data.users;
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
}
</style>