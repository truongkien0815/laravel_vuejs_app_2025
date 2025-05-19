<template>

  <div>
    <input type="text" v-model="categoryId" placeholder="Nhập Category ID" />
    <ul>
        {{ find}}
      <li v-for="product in products" :key="product.id">
        {{ product.name }}
      </li>
    </ul>


</div>
</template>

<script setup>
import { onMounted, ref, watch } from "vue";
import { useRoute } from "vue-router";



const products = ref([]);


onMounted(async() => {
   const response = await fetch(`/api/category`);
    const data = await response.json();

    products.value = data;
    

 console.log(data)
 
    
});

// onMounted(async() => {
//    const response = await fetch(`/api/cate_product`);
//     const data = await response.json();

//     products.value = data.users;
//      products.value = data.category;

//  console.log(data.users)
 
    
// });



// const categoryId = ref(); // hoặc từ dropdown, checkbox...

// fetch(`/api/cate_product?category_id=${categoryId}`)
//   .then(res => res.json())
//   .then(data => {
//     console.log(data.users);      
   
//   });

const categoryId = ref('')
const find = ref();

// Theo dõi thay đổi categoryId → tự động fetch lại sản phẩm
watch(categoryId, async (newVal) => {
  if (!newVal) {
    products.value = []
    return
  }

  try {
    const response = await fetch(`/api/cate_product?category_id=${newVal}`)
    const data = await response.json()
    if(!data.users || data.users.length === 0)
    {
         find.value = "không có";
    }
   else
   {
     products.value = data.users
find.value = "";
   }
  } catch (error) {
    console.error('Lỗi khi fetch dữ liệu:', error)
  }
})


</script>

<style>

</style>