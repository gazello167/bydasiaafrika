import { defineConfig } from 'astro/config';
import sitemap from '@astrojs/sitemap';

export default defineConfig({
  site: 'https://bydasiaafrika.com',
  output: 'static',
  trailingSlash: 'never',
  integrations: [
    sitemap({
      lastmod: new Date(),
    }),
  ],
  image: {
    // Gambar lokal di src/assets otomatis jadi WebP/AVIF responsif
    layout: 'constrained',
  },
  build: {
    inlineStylesheets: 'auto',
  },
});
