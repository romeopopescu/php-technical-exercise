import { defineConfig } from 'vitest/config';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
  plugins: [vue()],
  server: {
    // Forward GraphQL calls to the PHP API so the browser sees a single origin.
    proxy: {
      '/graphql': 'http://localhost:8080',
    },
  },
  test: {
    environment: 'jsdom',
    globals: true,
  },
});
