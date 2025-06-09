<template>
<div class="container">
      Chi tiết sản phẩm
     
    <div style="display:flex">
       Loại sp:
       <div v-for="item in category" :key="item.id">
   <span class="badge text-bg-info">{{ item.name}} </span>
</div>
    </div>

 <div class="name-product"><h5> {{ textname}}</h5></div>
 <div class="content" v-html="textcontent">
    
 </div>
</div>
</template>

<script setup>
import { ref, watchEffect } from "vue";
import { useRoute, useRouter } from "vue-router";

const route = useRoute();
const router = useRouter();
const productId = ref(route.params.id); 

const category = ref([]);
const textname = ref(""); 
const textcontent = ref("");
const fetchProduct = async () => {
  try {
    const response = await fetch(`/api/product_detail/${productId.value}`); // Gọi API lấy sản phẩm theo ID
    if (response.ok) {
      const data = await response.json();
      textname.value = data.flight.name; // Hiển thị tên sản phẩm trong input
       textcontent.value = data.flight.detail;
  category.value =data.category;
  console.log(data);
     
     
    } else {
      console.error("Không tìm thấy sản phẩm");
    }
  } catch (error) {
    console.error("Lỗi khi gọi API:", error);
  }
};
watchEffect(() => {
  if (productId.value) fetchProduct();
});

</script>

<style>

</style>