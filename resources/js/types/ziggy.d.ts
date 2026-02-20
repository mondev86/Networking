
/// <reference types="vite/client" />

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

