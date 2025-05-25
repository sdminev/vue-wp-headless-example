// vite.config.js
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  plugins: [vue()],
  server: {
    host: '0.0.0.0',
    port: 3000,
    proxy: {
      '/wp-json': {
        target: 'http://wordpress:80', // ← use service name, NOT localhost (за да сочи към правилния контейнер)
        changeOrigin: true,
        secure: false
      }
    }
  }
})
