import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

// khi chạy trên hosting

// export default defineConfig({
//   base: '/',
//   plugins: [
//     laravel(['resources/js/app.js']),
//     vue(),
//   ],
//   server: {
//     proxy: {
//       '/': 'http://localhost:8000' // Laravel server
//     },
//     fs: {
//       strict: false
//     }
//   }
// });


// khi chạy ở máy tính

export default defineConfig({
      // base: '/app/',
    plugins: [
        laravel(['resources/js/app.js']),
        vue({
            template: {
                transformAssetUrls: {
                    
                    base: null,

                  
                    includeAbsolute: false,
                },
            },
        }),
    ],
});