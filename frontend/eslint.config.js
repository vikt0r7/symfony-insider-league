// eslint.config.js
import vue from 'eslint-plugin-vue'
import tsPlugin from '@typescript-eslint/eslint-plugin'
import tsParser from '@typescript-eslint/parser'
import prettier from 'eslint-plugin-prettier'

export default async function () {
  return [
    {
      files: ['**/*.vue'],
      languageOptions: {
        parser: 'vue-eslint-parser',
        parserOptions: {
          parser: tsParser,
          ecmaVersion: 'latest',
          sourceType: 'module',
          extraFileExtensions: ['.vue'],
        },
      },
      plugins: {
        vue,
        prettier,
      },
      rules: {
        ...(await vue.configs['vue3-essential']).rules,
        'vue/multi-word-component-names': 'off',
        'vue/html-self-closing': ['error'],
        'prettier/prettier': 'error',
      },
    },
    {
      files: ['**/*.ts'],
      languageOptions: {
        parser: tsParser,
        parserOptions: {
          ecmaVersion: 'latest',
          sourceType: 'module',
        },
      },
      plugins: {
        '@typescript-eslint': tsPlugin,
        prettier,
      },
      rules: {
        '@typescript-eslint/no-explicit-any': 'warn',
        '@typescript-eslint/explicit-function-return-type': 'off',
        'prettier/prettier': 'error',
      },
    },
  ]
}
