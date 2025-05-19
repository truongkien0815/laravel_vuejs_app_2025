<template>
 <div class="container">
  <product_categoryVue/>
    <h1>Trang sản phẩm</h1>
    <!--  -->

<button class="btn btn-danger" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">Giỏ hàng</button>

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasRightLabel">Giỏ hàng</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <div class="cart" style="display:inline-grid">
      <!-- <button class="btn btn-info" @click="showCart">Xem giỏ hàng</button>
      <div v-if="cart.length > 0">
        <h3>Giỏ hàng:</h3>
        <ul>
          <li v-for="item in cart" :key="item.id">
            {{ item.name }} x{{ item.quantity }}
            <button @click="removeFromCart(item.id)" class="btn btn-danger">Xóa</button>
          </li>
        </ul>
      </div>
      <div v-else>
        <p>Giỏ hàng trống!</p>
      </div> -->

        <div class="cart-container">
    <!-- Danh sách giỏ hàng -->
    <div v-if="cart.length > 0" class="cart-items">
      <h3>Giỏ hàng của bạn</h3>
      <ul>
        <li v-for="item in cart" :key="item.id" class="cart-item">
          <span>{{ item.name }} - {{ item.price }} VNĐ</span>
          <div class="quantity">
            <!-- Chỉnh sửa số lượng sản phẩm -->
            <input type="number" v-model="item.quantity" @change="updateQuantity(item.id, item.quantity)" min="1" />
          </div>
          <button @click="removeFromCart(item.id)">Xóa</button>
        </li>
      </ul>
    </div>

    <!-- Giỏ hàng trống -->
    <div v-else class="empty-cart">
      <p>Giỏ hàng của bạn đang trống.</p>
    </div>
    </div>
    
    </div>
  </div>
</div>
        <!-- Giỏ hàng -->
    
 <div class="row">

    <div class="card" v-for="product in products" :key="product.id" >
  <img :src="product.image" class="card-img-top" alt="">
  <div class="card-body">
    <p class="card-text">{{ product.name}}</p>
     <router-link :to="'/' + product.id"> <div class="btn btn-warning">chi tiết</div></router-link>
       <button
            class="btn btn-primary"
            @click="addToCart(product)"
            >
            Thêm vào giỏ hàng
          </button>
  </div>
</div>

 </div>
  <section class="d-flex justify-content-center justify-content-lg-between p-4 border-bottom">
 

   
  </section>
<div class=" p-4">
  <div style=" display: flex; justify-content: center; align-items: center;">
  <button class="btn-custom" style="">Danh sách sản phẩm</button>
</div>
</div>

 <div class="slider swiper">
              <div class="swiper-wrapper">
                <div class="swiper-slide" v-for="product in products" :key="product.id">
                 
                    <router-link :to="'/' + product.id"> <img width="100%" :src="product.image" alt="Slide 1" /></router-link>
                    <div>
                       <router-link :to="'/' + product.id"> <div class="btn btn-warning">chi tiết</div></router-link>
                    </div>
                </div>
                
               
              </div>
              <!-- <div class="swiper-pagination"></div> -->
                <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
            </div>
  </div>
  
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useToast } from 'vue-toastification';
import product_categoryVue from './product_category.vue';

const router = useRouter();
const toast = useToast();
onMounted(() => {
  const swiper = new Swiper('.swiper', {
    loop: true,
     slidesPerView: 3,
      
    pagination: {
      el: '.swiper-pagination',
       clickable: true,
    },
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
      
    },
     breakpoints: {
           
            300: {
                slidesPerView: 2,
                spaceBetween: 10
            },
          
            1028: {
                slidesPerView: 3,
                spaceBetween: 30
            },
               1920: {
                slidesPerView: 3,
                spaceBetween: 30
            }
        }
  })
})

const products = ref([]);
onMounted(() => {
  fetch('/api/product_all')
    .then(response => response.json())
    .then(json => products.value = json);
});


// 

const cart = ref([]); // Giỏ hàng
// Khôi phục giỏ hàng từ localStorage khi trang được load
onMounted(() => {
  const savedCart = JSON.parse(localStorage.getItem("cart"));
  if (savedCart) {
    cart.value = savedCart;
  }
});

// Thêm sản phẩm vào giỏ hàng
const addToCart = (product) => {
  const existingProduct = cart.value.find((item) => item.id === product.id);
  if (existingProduct) {
    existingProduct.quantity++;
  } else {
    cart.value.push({ ...product, quantity: 1 });
  }

  // Lưu giỏ hàng vào localStorage
  localStorage.setItem("cart", JSON.stringify(cart.value));

  toast.success("Đã thêm sản phẩm vào giỏ hàng!");
};

// Xóa sản phẩm khỏi giỏ hàng
const removeFromCart = (productId) => {
  cart.value = cart.value.filter((item) => item.id !== productId);
  localStorage.setItem("cart", JSON.stringify(cart.value));
};

// Chỉnh sửa số lượng sản phẩm trong giỏ hàng
const updateQuantity = (productId, newQuantity) => {
  const product = cart.value.find((item) => item.id === productId);
  if (product && newQuantity > 0) {
    product.quantity = newQuantity; // Cập nhật số lượng
  }

  // Lưu giỏ hàng vào localStorage sau khi chỉnh sửa
  localStorage.setItem("cart", JSON.stringify(cart.value));

  alert(`Số lượng sản phẩm đã được cập nhật thành ${newQuantity}`);
};

// Hiển thị giỏ hàng
const showCart = () => {
  if (cart.value.length > 0) {
    alert("Giỏ hàng:\n" + cart.value.map(item => `${item.name} x${item.quantity}`).join("\n"));
  } else {
    alert("Giỏ hàng của bạn đang trống.");
  }
};
// 
</script>

<style>
.card{
  width: 25%;
}
.btn-custom{
      font-weight: 600;
  background: red;
    color: #fff;
    border: 1px red solid;
    padding: 5px 20px;
    border-radius: 50px;
}
.btn-custom:hover{
  background: blue;
}
@media (max-width: 767px) {
  .card{
  width: 50%;
}
}
</style>