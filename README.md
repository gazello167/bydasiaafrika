# bydasiaafrika.com (Astro)

Situs dealer BYD Jl. Asia Afrika, Bandung. Statis, cepat, siap Cloudflare Pages.

## Menjalankan

```bash
npm install
npm run dev      # http://localhost:4321
npm run build    # output ke dist/
npm run preview
```

## Struktur

```
src/
  data/        site.ts (NAP), models.ts (mobil), kredit.ts (parameter kredit)
  layouts/     BaseLayout.astro (meta, canonical, OG, schema)
  components/  Header, Footer, Schema (AutoDealer), ModelCard, KalkulatorKredit
  pages/       index, model/, model/[slug], kalkulator-kredit, test-drive, kontak, 404
  styles/      global.css
public/        robots.txt, favicon.svg
```

## Wajib diisi sebelum go-live

Cari `TODO` di `src/data/*`: nama dealer, alamat, telepon, WhatsApp, koordinat,
Google Maps embed, harga dan spesifikasi resmi tiap model, parameter kredit.
Pastikan NAP identik dengan Google Business Profile.

## Deploy ke Cloudflare Pages

1. Push repo ke GitHub.
2. Cloudflare Dashboard > Workers & Pages > Create > Pages > connect repo.
3. Build command: `npm run build`, output directory: `dist`.
4. Tambahkan custom domain `bydasiaafrika.com`.

## Tambah model baru

Tambahkan satu objek di `src/data/models.ts`. Halaman `/model/<slug>`, sitemap,
dan schema Product dibuat otomatis.

## Gambar

Taruh foto di `src/assets/` dan pakai komponen `<Image />` dari `astro:assets`
agar otomatis jadi WebP/AVIF responsif. Beri `loading="eager"` dan
`fetchpriority="high"` hanya untuk gambar hero.

## Catatan merek

Pastikan penggunaan nama/logo BYD sudah diizinkan prinsipal bila berstatus dealer resmi.
