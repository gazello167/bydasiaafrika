import type { ImageMetadata } from 'astro';

// Semua gambar di src/assets dipetakan di sini. Jika file belum ada, hasilnya undefined
// dan komponen memakai placeholder, jadi build tidak gagal saat foto belum siap.
const all = import.meta.glob<{ default: ImageMetadata }>('/src/assets/**/*.{jpg,jpeg,png,webp,avif}', {
  eager: true,
});

export function getImage(path?: string): ImageMetadata | undefined {
  if (!path) return undefined;
  return all[`/src/assets/${path}`]?.default;
}
