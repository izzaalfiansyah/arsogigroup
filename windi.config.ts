import { defineConfig } from "windicss/helpers";

export default defineConfig({
  darkMode: false,
  extract: {
    include: ['src/**/*.svelte', 'src/**/*.ts'],
    exclude: ['node_modules', '.git'],
  }
})