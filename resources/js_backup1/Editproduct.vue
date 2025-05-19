<template>
   <div class="container">
 <div> <label for="">tên sản phẩm</label></div>
      <input v-model="editedName" placeholder="Sửa tên sản phẩm" class=""/>
      
      <div> <label for="">tên Class</label></div>
         
      <div class="text-class"> <input v-model="editclass" placeholder="Nhập Class" /></div>

      <br>
  
      <img :src="img_product" alt="Ảnh sản phẩm" v-if="img_product" width="200px"/>
     <input type="file" name="img_product" @change="onFileChange" />
       
      <button class="btn btn-primary" @click="updateProduct(route.params.id)">edit</button>
    </div>
</template>

<script setup>
import { ref, watchEffect } from "vue";
import { useRoute, useRouter } from "vue-router";

const route = useRoute();
const router = useRouter();
const productId = ref(route.params.id); // Lấy ID từ URL
const editclass = ref();
const editedName = ref(""); // Tên sản phẩm
const img_product = ref("");
const selectedFile = ref(null); // Lưu file ảnh


// chọn file ảnh

const onFileChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    selectedFile.value = file; // Lưu file vào biến selectedFile
    img_product.value = URL.createObjectURL(file); // Hiển thị ảnh trước khi upload
  }
};


// 🟢 Hàm lấy thông tin sản phẩm
const fetchProduct = async () => {
  try {
    const response = await fetch(`/api/user_show/${productId.value}`); // Gọi API lấy sản phẩm theo ID
    if (response.ok) {
      const data = await response.json();
      editedName.value = data.name; // Hiển thị tên sản phẩm trong input
      editclass.value = data.class;
      img_product.value = data.image;
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
  // if (!selectedFile.value) {
  //   console.error("Chưa chọn file!");
  //   return;
  // }

  // console.log("File đã chọn:", selectedFile.value.name);

  const formData = new FormData();
  formData.append("name", editedName.value);
  formData.append("class", editclass.value);
  formData.append("image", selectedFile.value); // 🟢 Gửi file ảnh

  try {
    const response = await fetch(`/api/user_update/${id}`, {
      method: "POST", // 🛑 Laravel không hỗ trợ PUT với FormData, nên dùng POST
      body: formData, // ❌ Không dùng JSON.stringify()
    });

    const result = await response.json();
    console.log("Cập nhật thành công:", result);

    if (response.ok) {
      alert("Cập nhật thành công!");
      router.push("/");
    } else {
      alert("Cập nhật thất bại!");
    }
  } catch (error) {
    console.error("Lỗi khi cập nhật:", error);
  }
};

// const updateProduct = async (id) => {
//    console.log("File đã chọn:", selectedFile.value.name);
//     if (!selectedFile.value) {
//     console.error("Chưa chọn file!");
//     return;
//   }
//     const formData = new FormData();
//   formData.append("name", editedName.value);
//   formData.append("class", editclass.value);
//   formData.append("image", selectedFile.value); // 🟢 Gửi file ảnh

//   try {
//     const response = await fetch(`/api/user_update/${id}`, {
//       method: "PUT",
//       headers: { "Content-Type": "application/json" },
//       body: JSON.stringify({ name: editedName.value,
//         class: editclass.value,
//         image: img_product.value
//        }),
      
//     });

//     if (response.ok) {
      
//       const updatedProduct = todos.value.find(p => p.id === id);
//       if (updatedProduct) updatedProduct.name = editedName.value;
//  updatedProduct.class = editclass.value;
//   updatedProduct.image = img_product.value;
  
//       editingId.value = null; // Ẩn form sửa
      
//     } else {
//       alert("Cập nhật thất bại!");
//     }
//   } catch (error) {
//     console.error("Lỗi khi cập nhật:", error);
//   }
//   setTimeout(() => {
//         router.push('/');
//       });
// };
</script>

<style>

</style>