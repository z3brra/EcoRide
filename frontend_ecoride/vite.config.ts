import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import { fileURLToPath, URL } from 'url'

// https://vite.dev/config/
export default defineConfig({
  plugins: [react()],
  resolve: {
    alias: {
      "@provider": fileURLToPath(new URL('./src/provider', import.meta.url)),
      "@routes": fileURLToPath(new URL('./src/routes', import.meta.url)),
      "@layout": fileURLToPath(new URL('./src/layout', import.meta.url)),
      "@models": fileURLToPath(new URL('./src/models', import.meta.url)),
      "@assets": fileURLToPath(new URL('./src/assets', import.meta.url)),
      "@pages" : fileURLToPath(new URL('./src/pages', import.meta.url)),
      "@components" : fileURLToPath(new URL('./src/components', import.meta.url)),
      "@hook" : fileURLToPath(new URL('./src/hook', import.meta.url)),
      "@api" : fileURLToPath(new URL('./src/api', import.meta.url)),
      "@services" : fileURLToPath(new URL('./src/services', import.meta.url)),
      "@utils": fileURLToPath(new URL('./src/utils', import.meta.url)),
      // "@form": fileURLToPath(new URL('./src/components/form', import.meta.url)),
    }
  },
})
