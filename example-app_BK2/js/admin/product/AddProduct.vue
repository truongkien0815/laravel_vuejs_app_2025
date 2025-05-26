<template>
   <!-- Form thêm sản phẩm -->
    <div class="container">
     <div>
     <div > <label for="">tên sản phẩm</label></div>
     <input
  v-model="newProductName"
  :class="['form-control', errors.name ? 'is-invalid' : '']"
  @input="handleConvert" placeholder="Nhập tên sản phẩm"
/>
       <!-- <input v-model="newProductName"  placeholder="Nhập tên sản phẩm" @input="handleConvert"  /> -->
         <div v-if="errors.name" class="invalid-feedback">{{ errors.name }}</div>
     </div>
      <div><div><label for="">slug sản phẩm</label></div>
         <input v-model="newProductClass" class="form-control" placeholder="Nhập slug sản phẩm" /></div>
        <img :src="img_product" alt="Ảnh sản phẩm" v-if="img_product" width="200px"/>
     <input type="file" name="img_product" @change="onFileChange" />
      <div v-if="errors.image" class="text-danger small mt-1">{{ errors.image }}</div>
    
      <div>  <div><label for="">chi tiết sản phẩm</label></div>
      <!-- <textarea v-model="newProductDetail" placeholder="Nhập mô tả sản phẩm..." cols="40" rows="5"></textarea> -->
        <textarea v-model="newProductDetail" placeholder="Nhập mô tả sản phẩm..."  id="editor" rows="10" cols="80"></textarea>
        <div v-if="errors.detail" class="text-danger small mt-1">{{ errors.detail }}</div>
      <!-- <input v-model="newProductDetail" placeholder="Nhập chi tiết sản phẩm" /> -->
      </div>

<div>
  <div class="row">
    <div class="col-2">  <label>Chọn danh mục:</label></div>
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


      <button @click="addProduct">Thêm</button>
    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount  } from 'vue';
// chuyển hướng
import { useRouter } from 'vue-router';
import { useToast } from 'vue-toastification';
const toast = useToast();
const router = useRouter();
// 

import appcopy from '../../Appcopy.vue'

const categories =ref([]);
const categories_all =ref([]);
const selectedCategoryIds = ref([]); 

const todos = ref([]);
const newProductName = ref("");
const newProductClass = ref("");
const newProductDetail = ref("");
const editingId = ref(null);
const editedName = ref("");
const showId = ref();
const showName = ref();

const img_product = ref("");
const selectedFile = ref(null); // Lưu file ảnh

onMounted(() => {
  // Khởi tạo CKEditor sau khi component được mount
  if (window.CKEDITOR) {
    // CKEDITOR.replace('editor')
     CKEDITOR.replace('editor', {
    filebrowserBrowseUrl: '/ckfinder/browser', // Browse file
    filebrowserUploadUrl: '/media/upload',     // Upload file
    filebrowserImageBrowseUrl: '/ckfinder/browser?type=Images',
    filebrowserImageUploadUrl: '/media/upload?type=Images'
  });
  } else {
    console.error('CKEditor chưa được tải')
  }
})


// Nếu cần xoá instance khi unmount
onBeforeUnmount(() => {
  if (CKEDITOR.instances.editor) {
    CKEDITOR.instances.editor.destroy(true)
  }
})

// Lấy danh sách sản phẩm từ API
onMounted(() => {
  fetch('/api/category_all')
    .then(response => response.json())
    .then(json => {
     categories_all.value = json;
      // selectedCategoryIds.value = data.category.map(item => item.id); 
    });
   
});

const onFileChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    selectedFile.value = file; // Lưu file vào biến selectedFile
    img_product.value = URL.createObjectURL(file); // Hiển thị ảnh trước khi upload
  }
};

const errors = ref({
  name: '',
  image: '',
  detail: ''
})
// Thêm sản phẩm
// const addProduct = async () => {
//   if (!newProductName.value.trim()) {
//     alert("Vui lòng nhập tên sản phẩm!");
//     return;
//   }
//   // 
//   if (!selectedFile.value) {
//     alert("Vui lòng chọn ảnh");
//     return;
//   }

//   // console.log("File đã chọn:", selectedFile.value.name);

//   const formData = new FormData();
//     formData.append("name", newProductName.value);
//     formData.append("class", newProductClass.value);
//     // formData.append("detail", newProductDetail.value);
//     formData.append("detail", CKEDITOR.instances.editor.getData());
//     formData.append("image", selectedFile.value); // 🟢 Gửi file ảnh

//     selectedCategoryIds.value.forEach((id, index) => {
//       formData.append(`categories[${index}]`, id);
//     });
  
//   // 
//     try {
//     const response = await fetch(`/api/user_add`, {
//       method: "POST", // 🛑 Laravel không hỗ trợ PUT với FormData, nên dùng POST
//       body: formData, // ❌ Không dùng JSON.stringify()
//     });
//   // try {
//   //   const response = await fetch('./api/user_add', {
//   //     method: "POST",
//   //     headers: { "Content-Type": "application/json" },
//   //     body: JSON.stringify({ 
//   //       name: newProductName.value,
//   //       class: newProductClass.value,
//   //       detail: newProductDetail.value
//   //      }
     
//   //     ),
//   //   });

//     if (response.ok) {
//       const newProduct = await response.json();
//       todos.value.push(newProduct); // Thêm vào danh sách
//       toast.success("Thêm sản phẩm thành công!");
//       newProductName.value = ""; // Xóa input sau khi thêm
//       newProductClass.value = ""; 
//       newProductDetail.value =""
//     } else {
//       alert("Thêm sản phẩm thất bại!");
//     }
//   } catch (error) {
//     console.error("Lỗi khi thêm sản phẩm:", error);
//   }
//     setTimeout(() => {
//         router.push('/');
//       });
// };
const addProduct = async () => {
  errors.value = { name: '', image: '', detail: '' } // Reset lỗi

  const formData = new FormData()
  formData.append("name", newProductName.value)
  formData.append("class", newProductClass.value)
  formData.append("detail", CKEDITOR.instances.editor.getData())
  if (selectedFile.value) {
    formData.append("image", selectedFile.value)
  }

  selectedCategoryIds.value.forEach((id, index) => {
    formData.append(`categories[${index}]`, id)
  })

  try {
    const response = await fetch(`/api/user_add`, {
      method: "POST",
      body: formData
    })

    const result = await response.json()

    if (response.ok) {
      toast.success("Thêm sản phẩm thành công!")
      newProductName.value = ""
      newProductClass.value = ""
      newProductDetail.value = ""
      selectedFile.value = null
      img_product.value = null
      setTimeout(() => router.push('/'), 1000)
    } else if (response.status === 422) {
      // Laravel validation errors
      errors.value.name = result.errors.name?.[0] || ''
      errors.value.image = result.errors.image?.[0] || ''
      errors.value.detail = result.errors.detail?.[0] || ''
    } else {
      toast.error("Thêm sản phẩm thất bại!")
    }
  } catch (error) {
    console.error("Lỗi khi thêm sản phẩm:", error)
  }
}

// const result = ref('');

function handleConvert() {
  newProductClass.value = convertName(newProductName.value);
}

function convertName(str) {
  str = str.replace(/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/g, 'a');
  str = str.replace(/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/g, 'e');
  str = str.replace(/(ì|í|ị|ỉ|ĩ)/g, 'i');
  str = str.replace(/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/g, 'o');
  str = str.replace(/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/g, 'u');
  str = str.replace(/(ỳ|ý|ỵ|ỷ|ỹ)/g, 'y');
  str = str.replace(/(đ)/g, 'd');
  str = str.replace(/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/g, 'A');
  str = str.replace(/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/g, 'E');
  str = str.replace(/(Ì|Í|Ị|Ỉ|Ĩ)/g, 'I');
  str = str.replace(/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/g, 'O');
  str = str.replace(/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/g, 'U');
  str = str.replace(/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/g, 'Y');
  str = str.replace(/(Đ)/g, 'D');
  str = str.replace(/(\“|\”|\‘|\’|,|!|&|;|@|#|%|~|`|=|_|'|\]|\[|\}|\{|\)|\(|\+|\^|\?|\>|\<|\:)/g, '-');
  str = str.replace(/ /g, '-');
  return str;
}

</script>

<style>

</style>