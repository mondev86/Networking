/// <reference types="vite/client" />
/// <reference types="vue/macros-global" />

// Permite importar cualquier archivo .vue
declare module '*.vue' {
  import type { DefineComponent } from 'vue';
  const component: DefineComponent<Record<string, unknown>, Record<string, unknown>, unknown>;
  export default component;
}

// Tipado global para Ziggy route()
import type { Config, RouteParam } from 'ziggy-js';

declare global {
  function route(
    name?: string,
    params?: RouteParam | RouteParam[],
    absolute?: boolean,
    config?: Config
  ): string;
}

// Extensión de Vue Router (opcional, si usas $route)
import 'vue-router';
declare module 'vue-router' {
  interface RouteMeta {
    requiresAuth?: boolean;
  }
}

// Interfaces de dominio y props de Inertia
export interface Ticket {
  id: number;
  title: string;
  description: string;
  status: string;
  priority: string;
  created_at: string;
  resolved_at?: string;
  user: { id: number; name: string };
  device?: { id: number; name: string };
  assigned_admin?: { id: number; name: string };
  responses?: Array<{
    id: number;
    message: string;
    is_internal: boolean;
    created_at: string;
    user: { id: number; name: string };
    user_id: number;
  }>;
}

export interface PageProps {
  auth: {
    user: {
      id: number;
      name: string;
      email: string;
      role: string;
    };
  };
  flash?: {
    success?: string;
    error?: string;
  };
}

export {};

