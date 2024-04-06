import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path';

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'src')
    }
  },
  // If want to visit the application on your mobile phone
  // server: {
  //   host: '0.0.0.0',
  //   port: 5173,
  //   strictPort: true,
  // }
  
})