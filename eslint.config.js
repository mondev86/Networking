import prettier from 'eslint-config-prettier/flat';
import vue from 'eslint-plugin-vue';

import { defineConfigWithVueTs, vueTsConfigs } from '@vue/eslint-config-typescript';

export default defineConfigWithVueTs(
    vue.configs['flat/essential'],
    vueTsConfigs.recommended,
    {
        ignores: [
            'vendor/**',
            'Networking/**',
            'node_modules/**',
            'public/**',
            'bootstrap/**',
            'storage/**',
            'tailwind.config.js',
            'resources/js/Components/ui/**',
        ],
    },
    {
        rules: {
            'vue/multi-word-component-names': 'off',
            'vue/block-lang': 'off',
            '@typescript-eslint/no-explicit-any': 'off',
            '@typescript-eslint/no-unused-vars': ['error', { 'argsIgnorePattern': '^_', 'varsIgnorePattern': '^_' }],
        },
    },
    prettier,
);
