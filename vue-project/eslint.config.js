import { defineConfig } from '@eslint/config'
import globals from 'globals'
import js from '@eslint/js'
import pluginVue from 'eslint-plugin-vue'

export default defineConfig([
  {
    ignores: ['**/dist/**', '**/dist-ssr/**', '**/coverage/**']
  },
  {
    name: 'app/files-to-lint',
    files: ['**/*.{js,mjs,jsx,vue}'],
    languageOptions: {
      globals: {
        ...globals.browser
      }
    }
  },
  js.configs.recommended,
  ...pluginVue.configs['flat/essential']
])
