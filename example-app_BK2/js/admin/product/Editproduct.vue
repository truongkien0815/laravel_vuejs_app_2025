<template>
   <div class="container">
      <div> <label for="">tên sản phẩm</label></div>
      <input v-model="editedName" placeholder="Sửa tên sản phẩm" class="form-control"/>
      <div> <label for="">tên slug</label></div>   
      <div class="text-class"> <input class="form-control" v-model="editclass" placeholder="Nhập slug"  /></div>
      
       <div> <label for="">Chi tiết</label></div>   
      <textarea name="" v-model="editdetail" id="editor"   cols="30" rows="10"></textarea>
      <br>
    
      <img :src="img_product" alt="Ảnh sản phẩm" v-if="img_product" width="200px"/>
      <input type="file" name="img_product" @change="onFileChange" />

      
<div>
  <div class="row">
    <div class="col-2"> <label>Chọn danh mục:</label></div>
    <div class="col-10">
      <div v-for="item in categories_all" :key="item.id">
  <label>
    <input
      type="checkbox"
      :value="item.id"
      v-model="selectedCategoryIds"
    />
    <span :style="{ color: selectedCategoryIds.includes(item.id) ? 'black' : 'gray' }">
      {{ item.name }}
    </span>
  </label>
</div>
    </div>
  </div>
  



<!-- <select multiple v-model="selectedCategoryIds">
  <option
    v-for="item in categories_all"
    :key="item.id"
    :value="item.id"
    :style="{ color: selectedCategoryIds.includes(item.id) ? 'black' : 'gray' }"
  >
    {{ item.name }}
  </option>
</select> -->
</div>

      <button class="btn btn-primary" @click="updateProduct(route.params.id)">edit</button>
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
const editclass = ref();
const editedName = ref(""); // Tên sản phẩm
const editdetail = ref(""); // Tên sản phẩm
const img_product = ref("");
const selectedFile = ref(null); // Lưu file ảnh

const categories = ref([]);
const categories_all = ref([]);
const selectedCategoryIds = ref([]); // Gán ID những category đã chọn

// onMounted(() => {
//   if (window.CKEDITOR) {
//     CKEDITOR.replace("editor");

//     CKEDITOR.instances.editor.on("instanceReady", () => {
//       if (editdetail.value) {
//         CKEDITOR.instances.editor.setData(editdetail.value);
//       }
//     });
//   }
// });
// onMounted(() => {
//   // Khởi tạo CKEditor sau khi component được mount
//   if (window.CKEDITOR) {
//     CKEDITOR.replace('editor')
//   } else {
//     console.error('CKEditor chưa được tải')
//   }
// })
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
      editedName.value = data.flight.name; // Hiển thị tên sản phẩm trong input
      editclass.value = data.flight.class;
      img_product.value = data.flight.image;
      editdetail.value = data.flight.detail;

        // CKEDITOR.instances.editor.setData(data.flight.detail);  // Gán nội dung vào CKEditor
      
       categories.value = data.category;
  selectedCategoryIds.value = data.category.map(item => item.id); // Mảng các id category đã gán
     
      categories_all.value = data.categories;
      
 if (window.CKEDITOR) {
    // CKEDITOR.replace("editor");
      CKEDITOR.replace('editor', {
    filebrowserBrowseUrl: '/ckfinder/browser', // Browse file
    filebrowserUploadUrl: '/media/upload',     // Upload file
    filebrowserImageBrowseUrl: '/ckfinder/browser?type=Images',
    filebrowserImageUploadUrl: '/media/upload?type=Images'
  });

    CKEDITOR.instances.editor.on("instanceReady", () => {
      if (editdetail.value) {
        CKEDITOR.instances.editor.setData(editdetail.value);
      }
    });
  }

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
  formData.append("detail", CKEDITOR.instances.editor.getData()); 
  selectedCategoryIds.value.forEach((id, index) => {
    formData.append(`categories[${index}]`, id);
  });
  try {
    const response = await fetch(`/api/user_update/${id}`, {
      method: "POST", // 🛑 Laravel không hỗ trợ PUT với FormData, nên dùng POST
      body: formData, // ❌ Không dùng JSON.stringify()
    });

    const result = await response.json();
    // console.log("Cập nhật thành công:", result);

    if (response.ok) {
      // alert("Cập nhật thành công!");
      toast.info("Cập nhật thành công!");
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
select[multiple] {
  width: max-content;
  
  padding: 5px;
  font-size: 14px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

</style>