import { createRouter, createWebHistory } from 'vue-router'
import App from './App.vue'
import Home from './Home.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home,
    },
    {
      path: '/about',
      name: 'about',
      component: () => import('./Appcopy.vue'),
    },
    {
      path: '/contact',
      name: 'contact',
      component: () => import('./Contact.vue'),
    },
    {
      path: '/addproduct',
      name: 'addproduct',
      component: () => import('./AddProduct.vue'),
    },
    {
      path: '/editproduct/:id',
      name: 'editproduct',
      component: () => import('./Editproduct.vue'),
    },
  ],
})

export default router
