

<script setup>


import header_vue from "./layout/header.vue";
import footer_vue from "./layout/footer.vue";

// const token2 =  ref(localStorage.getItem('token2'));


// import { RouterView, RouterLink } from 'vue-router';


</script>



<template>
 <header_vue/>

  <!-- Hiển thị component theo route -->
  <router-view />
  <!-- <appcopy/> -->
  <footer_vue/>


</template>









