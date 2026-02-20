// resources/js/bootstrap.ts

import axios, { AxiosInstance } from 'axios';

// Declaramos el tipo global para que VS Code/TS reconozca window.axios
declare global {
    interface Window {
        axios: AxiosInstance;
    }
}

// Inicializamos axios en el objeto global window
window.axios = axios;

// Configuración por defecto de axios
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Opcional: si usas CSRF token de Laravel
const token = document.head.querySelector<HTMLMetaElement>('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: please add <meta name="csrf-token" content="{{ csrf_token() }}"> in your <head>.');
}
