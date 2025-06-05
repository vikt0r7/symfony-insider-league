import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import { fileURLToPath, URL } from 'node:url'

export default defineConfig({
  root: '.', // 👈 обязательно
  server: {
    proxy: {
      '/api': 'http://localhost:8080',
    },
  },
  plugins: [vue()],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url)),
    },
  },
  build: {
    outDir: '../public/build',
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: './index.html', // 👈 оставляем как есть
    },
  },
})
