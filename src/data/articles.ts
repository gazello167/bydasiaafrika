// Konten "Discover the Latest". Semua artikel di bawah adalah CONTOH (draft: true):
// tampil saat `npm run dev`, tetapi TIDAK dipublish ke produksi sampai draft dihapus.
export type ArticleCategory = 'News & Event' | 'Insight' | 'Promo';
export const articleCategories: ArticleCategory[] = ['News & Event', 'Insight', 'Promo'];

export interface Article {
  slug: string;
  title: string;
  category: ArticleCategory;
  date: string; // YYYY-MM-DD
  excerpt: string;
  body: string[]; // satu elemen = satu paragraf
  image?: string; // path relatif ke src/assets, mis. "news/grand-opening.webp"
  draft?: boolean;
}

import { cmsFetch } from '../lib/cms';

export const articles: Article[] = [
  {
    slug: 'grand-opening-showroom',
    title: 'Grand opening showroom (contoh)',
    category: 'News & Event',
    date: '2026-11-10',
    excerpt: 'Contoh berita acara. Ganti dengan berita sungguhan.',
    body: ['TODO: tulis isi artikel.'],
    draft: true,
  },
  {
    slug: 'biaya-charging-mobil-listrik-bandung',
    title: 'Panduan biaya charging mobil listrik di Bandung (contoh)',
    category: 'Insight',
    date: '2026-11-05',
    excerpt: 'Contoh artikel insight. Konten panduan seperti ini bagus untuk SEO lokal.',
    body: ['TODO: tulis isi artikel.'],
    draft: true,
  },
  {
    slug: 'promo-akhir-tahun',
    title: 'Promo akhir tahun (contoh)',
    category: 'Promo',
    date: '2026-11-01',
    excerpt: 'Contoh promo. Cantumkan periode dan syarat yang jelas.',
    body: ['TODO: tulis isi artikel.'],
    draft: true,
  },
  {
    slug: 'pajak-mobil-listrik-jawa-barat',
    title: 'Pajak mobil listrik di Jawa Barat (contoh)',
    category: 'Insight',
    date: '2026-10-25',
    excerpt: 'Contoh artikel insight tentang pajak kendaraan listrik.',
    body: ['TODO: tulis isi artikel.'],
    draft: true,
  },
  {
    slug: 'event-test-drive-weekend',
    title: 'Event test drive akhir pekan (contoh)',
    category: 'News & Event',
    date: '2026-10-20',
    excerpt: 'Contoh pengumuman event.',
    body: ['TODO: tulis isi artikel.'],
    draft: true,
  },
  {
    slug: 'program-trade-in',
    title: 'Program trade in (contoh)',
    category: 'Promo',
    date: '2026-10-15',
    excerpt: 'Contoh promo trade in.',
    body: ['TODO: tulis isi artikel.'],
    draft: true,
  },
];

// Normalisasi baris DB (snake_case) ke bentuk frontend.
function normalizeArticle(raw: any): Article {
  const arr = (v: any) => (Array.isArray(v) ? v : typeof v === 'string' ? JSON.parse(v || '[]') : []);
  return {
    slug: raw.slug,
    title: raw.title,
    category: raw.category,
    date: (raw.date ?? raw.published_at ?? '').slice(0, 10),
    excerpt: raw.excerpt ?? '',
    body: arr(raw.body).length ? arr(raw.body) : [raw.content ?? ''].filter(Boolean),
    image: raw.image ?? undefined,
    draft: Boolean(raw.draft ?? (raw.status === 'draft')),
  };
}

// Ambil artikel dari CMS; fallback ke data lokal bila API mati/belum di-set.
export async function getArticles(): Promise<Article[]> {
  const remote = await cmsFetch<any[]>('articles');
  return remote?.length ? remote.map(normalizeArticle) : articles;
}

// Draft hanya tampil saat development.
export const publishedArticles = (list: Article[] = articles) =>
  list
    .filter((a) => !a.draft || import.meta.env.DEV)
    .sort((a, b) => b.date.localeCompare(a.date));

export const formatTanggal = (iso: string) =>
  new Intl.DateTimeFormat('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }).format(new Date(iso));
