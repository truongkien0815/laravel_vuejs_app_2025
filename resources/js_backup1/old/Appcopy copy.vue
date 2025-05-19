<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const products = ref([]);
const todos = ref([]);
   onMounted (()=>{
 fetch('./api/user_all')
      .then(response => response.json())
      .then(json => todos.value =json)
   })


// Hàm xóa sản phẩm
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

  <div>
    <h1>Danh sách sản phẩm 1</h1>
    <ul>
       <table border="1">
          <tr>
        <th>id</th>
        <th>name</th>
      </tr>
    
    
        <tr v-for="product in todos" :key="product.id">
          <th> <a href="#" id="id_user">{{ product.id }}</a>  </th>
          <th>  {{ product.name }}  </th>
          <th>
          <button @click="deleteProduct(product.id)">Xóa</button>
        </th>
        </tr>
       
     
      </table>

     

      
    </ul>
  </div>
</template>
