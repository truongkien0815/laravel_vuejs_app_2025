<template>
<div class="container p-4">
    <div class="form-login">
   <div>
     <!-- <input type="email" v-model="email" placeholder="Email" /> -->
      <input class="form-control w-100"
      :class="[ errors.email ? 'is-invalid' : '']" type="email" v-model="email" placeholder="Email" />
      <div v-if="errors.email" class="invalid-feedback">{{ errors.email }}</div>
   </div>
   <div class="txt mt-3">
     <input class="form-control w-100"  :class="[ errors.password ? 'is-invalid' : '']" type="password" v-model="password" placeholder="Mật khẩu" />
     <div v-if="errors.password" class="invalid-feedback">{{ errors.password }}</div>
   </div>
    <button class="btn btn-danger mt-3" @click="login">Đăng nhập</button>
  </div>
</div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";
import { useRoute, useRouter } from "vue-router";
import { useToast } from "vue-toastification";
const toast = useToast();


 
    const email = ref("");
    const password = ref("");
     const errors = ref({email:"",password:"",})
    const route = useRoute();
    const router = useRouter();

    const login = async () => {
      try {
        const response = await axios.post("/api/login", {
          email: email.value,
          password: password.value,
        });

        // Lưu token vào localStorage
        localStorage.setItem("token", response.data.authorization.token);
        // console.log(localStorage.getItem("token"));

        localStorage.setItem("token2", response.data.user.name);

        // console.log("Đăng nhập thành công:", response.data);

        router.push("/");
      } catch (error) {
        // thêm vào kiểm tra lỗi
          if (error.response) {
      const status = error.response.status;

      // Lỗi validate từ Laravel
      if (status === 422) {
        const errorst = error.response.data.errors;
     
  errors.value.email = errorst.email?.[0] || "";
   errors.value.password = errorst.password?.[0] || "";
        console.error(errorst);
      }
      // Sai tài khoản hoặc mật khẩu
      else if (status === 401) {
         errors.value.email = "";
   errors.value.password =  "";
        alert("Đăng nhập không thành công. Vui lòng kiểm tra lại thông tin.");
      }
      // Các lỗi khác
      else {
        alert("Đã xảy ra lỗi. Vui lòng thử lại sau.");
        console.error("Lỗi đăng nhập:", error.response);
      }
    } else {
      alert("Không thể kết nối đến máy chủ.");
      console.error("Lỗi kết nối:", error);
    }
// thêm vào kiểm tra lỗi
        // console.error("Đăng nhập thất bại:", error.response.data);
        // alert("Sai email hoặc mật khẩu");
      }
    };

   


// console.log(localStorage.getItem("token"));
</script>

<style>
input{
  margin: auto;
}
.form-login{
  text-align: center;
  box-shadow: 0 4px 10px 0 rgba(0, 0, 0, 0.2), 0 4px 20px 0 rgba(0, 0, 0, 0.19);
  padding: 20px;
  margin: auto;
  width: 400px;
  

}
@media (max-width: 768px) {
  .form-login{
    width: 100%;
  }
}
</style>