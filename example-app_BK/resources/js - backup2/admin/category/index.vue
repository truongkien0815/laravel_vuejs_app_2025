<template>
  <div class="container">
    Danh mục sản phẩm
  
    <table  border="1" style="width: 100%">
       <tr>
        <th class="th" style="width: 5%">id</th>
        <th class="th" style="width: 20%">name</th>
        <th class="th" style="width: 30%">Hành động</th>
       </tr>
       <tr v-for="item in todos" :key="item.id">
        <td>  {{item.id}}</td>
        <td>  {{item.name}}</td>
         <td> 
          <router-link :to="'/addcategory/' "> <div class="btn btn-warning">Thêm</div></router-link>
         <div class="btn">Xóa</div>
          <router-link :to="'/editcategory/' + item.id"> <div class="btn btn-warning">Edit</div></router-link>
         </td>
       </tr>
    </table>
  </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
const todos = ref([]);

onMounted(() => {
  fetch('/api/category')
    .then(response => response.json())
    .then(json => {
        todos.value = json;
          console.log(todos.value);
    }
    );
  
});
</script>

<style>

</style>