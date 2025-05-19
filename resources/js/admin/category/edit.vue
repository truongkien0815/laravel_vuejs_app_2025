<template>
 
<div class="div container">
 <div> edit danh mục sản phẩm</div>

 <div class="form-group pt-2 pb-3">
                                        <label for="name">name</label>
                                         <div>
                                          <input v-model="editname" type="text">
                                         </div>
                                    
                                    </div>
 
 <button class="btn btn-primary" style="" @click="updateProduct(route.params.id)">edit</button>
</div>

  
</template>

<script setup>
import { onMounted, ref, watchEffect } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useToast } from "vue-toastification";


const toast = useToast();

const route = useRoute();
const router = useRouter();
const productId = ref(route.params.id); // Lấy ID từ URL
const editname = ref();
// 🟢 Hàm lấy thông tin sản phẩm
const fetchProduct = async () => {
  try {
    const response = await fetch(`/api/category/${productId.value}`); // Gọi API lấy sản phẩm theo ID
    if (response.ok) {
      const data = await response.json();
    console.log(data.flight);
      editname.value= data.flight.name;


    } else {
      console.error("Không tìm thấy sản phẩm");
    }
  } catch (error) {
    console.error("Lỗi khi gọi API:", error);
  }
};


// 🟢 Khi đường dẫn thay đổi, tự động lấy sản phẩm mới
watchEffect(() => {
  if (productId.value) fetchProduct();
});

const updateProduct = async (id) => {
  

  const formData = new FormData();
  formData.append("name", editname.value);
 
  try {
    const response = await fetch(`/api/category_update/${id}`, {
      method: "POST", // 🛑 Laravel không hỗ trợ PUT với FormData, nên dùng POST
      body: formData, // ❌ Không dùng JSON.stringify()
    });

    const result = await response.json();
    console.log("Cập nhật thành công:", result);

    if (response.ok) {
      toast("Cập nhật thành công!");
      // alert("Cập nhật thành công!");
      router.push("/category");
    } else {
      alert("Cập nhật thất bại!");
    }
  } catch (error) {
    console.error("Lỗi khi cập nhật:", error);
  }
};
</script>

<style>

</style>