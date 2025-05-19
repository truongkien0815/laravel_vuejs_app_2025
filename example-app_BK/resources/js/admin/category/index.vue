<template>
  <div class="container">
    <div>Danh mục sản phẩm</div>
  
   <router-link :to="'/addcategory/' "> <div class="btn btn-success">Thêm</div></router-link>
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
         
         <div class="btn btn-danger" @click="deleteCategory(item.id)">Xóa</div>
         
          <router-link :to="'/editcategory/' + item.id"> <div class="btn btn-warning">Edit</div></router-link>
         </td>
       </tr>
    </table>
  </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import { useToast } from 'vue-toastification';

const todos = ref([]);

const toast = useToast()

function notifySuccess() {
  toast.error('Xóa thành công!')
}

onMounted(() => {
  fetch('/api/category')
    .then(response => response.json())
    .then(json => {
        todos.value = json;
        
    }
    );
  
});

const deleteCategory = async (id) => {
  if (confirm("Bạn có chắc chắn muốn xóa?")) {
    try {
      const token = localStorage.getItem("token");
      const response = await fetch(`/api/category_delete/${id}`, {
        method: "DELETE",
        headers: {
          Authorization: `Bearer ${token}`,
          "Content-Type": "application/json",
        },
      });
      if (response.ok) {
        todos.value = todos.value.filter((product) => product.id !== id);
      
        notifySuccess();
      } else {
        toast.error("Xóa thất bại! Kiểm tra lại quyền truy cập.");
      }
    } catch (error) {
      console.error("Lỗi khi xóa:", error);
    }
  }
};

</script>

<style>

</style>