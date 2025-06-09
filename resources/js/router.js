import { createRouter, createWebHistory } from 'vue-router'
import App from './App.vue'
import Home from './admin/product/Home.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home,
    },
     {
      path: '/addproduct',
      name: 'addproduct',
      component: () => import('./admin/product/AddProduct.vue'),
    },
    {
      path: '/editproduct/:id',
      name: 'editproduct',
      component: () => import('./admin/product/Editproduct.vue'),
    },

    // category
     {
      path: '/category',
      name: 'category',
      component: () => import('./admin/category/index.vue'),
    },
    {
      path: '/editcategory/:id',
      name: 'editcategory',
      component: () => import('./admin/category/edit.vue'),
    },
     {
      path: '/addcategory',
      name: 'addcategory',
      component: () => import('./admin/category/add.vue'),
    },

    // 

     {
      path: '/cart',
      name: 'cart',
      component: () => import('./cart.vue'),
    },

    {
      path: '/about',
      name: 'about',
      component: () => import('./page/About.vue'),
    },
    {
      path: '/contact',
      name: 'contact',
      component: () => import('./Product.vue'),
      // 
  //      children: [
  //   {
  //     path: '/about2/:id',

  //     component: () => import('./product_category2.vue')
  //   }
  // ]
      // 
      
    },

     {
      path: '/about2/:id',
      name: 'about2',
       component: () => import('./product_category2.vue')
      // 
 
  //   {
  //     path: '/about2/:id',

  //     component: () => import('./product_category2.vue')
  //   }

      // 
      
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('./login/login.vue'),
    },

    // 
    {
      path: '/:id',
      name: 'single_post',
      component: () => import('./product/single.vue'),
    },
  ],
})

export default router
