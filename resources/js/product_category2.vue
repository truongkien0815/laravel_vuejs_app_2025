<template>
  <div class="container">

 <div class="row">
<h4>{{ category_name}}</h4>
 {{ null_1}}
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
   
  </div>
   <router-view />
</template>


<script setup>
// kế thừa addtoCart từ file Contact me
defineProps({
  addToCart: Function
});
// 
import { onMounted, ref, watch, watchEffect } from "vue";
import { useRoute, RouterLink } from 'vue-router';

const route = useRoute();
const products = ref([]);
const productsall = ref([]);
const loading = ref(false);

const null_1 =ref();


const products_all = ref([]);


const categoryId = ref(route.params.id); 
const find = ref();
const activeCategoryId = ref(null);
const category_name =ref();
// 
const fetchProduct = async () => {
   activeCategoryId.value = categoryId.value; 
  try {
    const response = await fetch(`/api/cate_product/${categoryId.value}`);
    if (response.ok) {
      const data = await response.json();
      if(!data.users || data.users.length === 0)
      {
        null_1.value = "không có sản phẩm";
         products_all.value = null;
      }
       else{
         null_1.value = "";
         products_all.value = data.users;
       }
        category_name.value = data.category.name;
    } else {
      console.error("Không tìm thấy sản phẩm");
    }
  } catch (error) {
    console.error("Lỗi khi gọi API:", error);
  }
};

watch(() => route.params.id, (newId) => {
  categoryId.value = newId;
  fetchProduct();
});

// Gọi lần đầu
onMounted(() => {
  fetchProduct();
});
// watchEffect(() => {
//   if (categoryId.value) fetchProduct();
// });
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
.card{
  width: 25%;
}
@media (max-width: 767px) {
  .card{
  width: 50%;
}
}
</style>


