/// <reference types="vite/client" />

// Tipado para cualquier archivo .vue (incluye alias @/*)
declare module '*.vue' {
  import type { DefineComponent } from 'vue';
  const component: DefineComponent<Record<string, unknown>, Record<string, unknown>, unknown>;
  export default component;
}

// Extiende las propiedades de Vue para reconocer $route de vue-router y route() de Ziggy
import type { RouteLocationNormalizedLoaded } from 'vue-router';
import type { Config, RouteParam } from 'ziggy-js';

declare module '@vue/runtime-core' {
  interface ComponentCustomProperties {
    $route: RouteLocationNormalizedLoaded;
    route: (
      name?: string,
      params?: RouteParam | RouteParam[],
      absolute?: boolean,
      config?: Config
    ) => string;
  }
}

// Declaración global para la función route() de Ziggy
import type { Config, RouteParam } from 'ziggy-js';

declare global {
  function route(
    name?: string,
    params?: RouteParam | RouteParam[],
    absolute?: boolean,
    config?: Config
  ): string;
}

export {};
