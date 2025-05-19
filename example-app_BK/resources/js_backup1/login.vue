<template>
  <div>
    <input type="email" v-model="email" placeholder="Email">
    <input type="password" v-model="password" placeholder="Mật khẩu">
    <button @click="login">Đăng nhập</button>
  </div>
</template>

<script>
import { ref } from 'vue';
import axios from 'axios';
import { useRoute, useRouter } from "vue-router";



export default {
  setup() {
    const email = ref('');
    const password = ref('');
const route = useRoute();
const router = useRouter();

    const login = async () => {
      try {
        const response = await axios.post('./api/login', {
          email: email.value,
          password: password.value
        });

        // Lưu token vào localStorage
        localStorage.setItem('token', response.data.authorization.token);
console.log(localStorage.getItem('token'));

  localStorage.setItem('token2', response.data.user.name);

        console.log('Đăng nhập thành công:', response.data);
          
        router.push('/');

      } catch (error) {
         console.error('Đăng nhập thất bại:', error.response.data);
        alert('Sai email hoặc mật khẩu');
        
      }
    };

    return { email, password, login };
  }
};
console.log(localStorage.getItem('token'));
</script>
