<template>
  <div class="container">
    add danh mục sản phẩm

<div class="form-group pt-2 pb-3">
                                        <label for="name">name</label>
                                         <div> <input v-model="addname"  type="text" class="form-control" placeholder="Tên category"></div>
                                    
                                    </div>

    
 <button class="btn btn-primary" style="" @click="addCategory">Add</button>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter, useRoute } from 'vue-router';
import { useToast } from "vue-toastification";
const toast = useToast();
const router = useRouter();
 const addname = ref();
const addCategory = async () => {
  if (addname.value == null) {
    alert("Vui lòng nhập tên sản phẩm!");
    return;
  }

  const formData = new FormData();
    formData.append("name", addname.value);
    try {
    const response = await fetch(`/api/category_add`, {
      method: "POST", // 🛑 Laravel không hỗ trợ PUT với FormData, nên dùng POST
      body: formData, // ❌ Không dùng JSON.stringify()
    });


    if (response.ok) {
      const newProduct = await response.json();
      todos.value.push(newProduct); // Thêm vào danh sách
     
      addname.value = ""; // Xóa input sau khi thêm
     
    } else {
      alert("Thêm sản phẩm thất bại!");
    }
  } catch (error) {
    console.error("Lỗi khi thêm sản phẩm:", error);
  }
    setTimeout(() => {
       toast.success("Thêm thành công");
        router.push('/category');
      });
};
</script>

<style>

</style>