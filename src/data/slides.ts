// Slide hero. Konten diambil dari CMS (PUBLIC_CMS_API_URL) saat build;
// jika API tidak tersedia, fallback ke data lokal di bawah.
// Gambar: URL absolut R2 (https://...) atau path relatif R2 (mis. "hero/nama.webp").
import { cmsFetch } from '../lib/cms';

export interface Slide {
  eyebrow: string;
  title: string;
  text: string;
  cta: { label: string; href: string };
  image?: string;
}

const localSlides: Slide[] = [
  {
    eyebrow: 'Test Drive',
    title: 'Rasakan mobil listrik BYD di Bandung',
    text: 'Datang ke showroom kami di Jl. Asia Afrika dan jadwalkan test drive.',
    cta: { label: 'Jadwalkan Test Drive', href: '/test-drive' },
  },
  {
    eyebrow: 'Simulasi Kredit',
    title: 'Hitung cicilan sebelum datang',
    text: 'Atur DP, tenor, dan bunga, lalu kirim hasilnya ke sales via WhatsApp.',
    cta: { label: 'Simulasi Kredit', href: '/kalkulator-kredit' },
  },
  {
    eyebrow: 'Promo', // TODO: ganti dengan promo yang berlaku
    title: 'Promo bulan ini (TODO)',
    text: 'Tulis detail promo resmi di sini, lengkap dengan periode berlaku.',
    cta: { label: 'Lihat Pricelist', href: '/pricelist' },
  },
];

// Normalisasi baris DB (snake_case) ke bentuk frontend (camelCase).
function normalizeSlide(raw: any): Slide {
  return {
    eyebrow: raw.eyebrow ?? '',
    title: raw.title ?? '',
    text: raw.text ?? '',
    cta: {
      label: raw.cta?.label ?? raw.cta_label ?? 'Selengkapnya',
      href: raw.cta?.href ?? raw.cta_href ?? '#',
    },
    image: raw.image ?? undefined,
  };
}

// Ambil slide dari CMS; fallback ke data lokal bila API mati/belum di-set.
export async function getSlides(): Promise<Slide[]> {
  const remote = await cmsFetch<any[]>('slides');
  return remote?.length ? remote.map(normalizeSlide) : localSlides;
}
