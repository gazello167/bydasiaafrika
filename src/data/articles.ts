// File ini DITULIS ULANG oleh CMS PHP (admin/api/articles.php & n8n). Jangan edit manual.
export interface Topic {
  slug: string;
  label: string;
  description: string;
  modelSlug?: string;
  image?: string;
}

export const topics: Topic[] = [
  { slug: 'atto-1', label: 'BYD Atto 1', modelSlug: 'atto-1', description: 'Semua tentang BYD Atto 1: harga, jarak tempuh, charging, dan tips pemakaian.' },
  { slug: 'atto-3', label: 'BYD Atto 3', modelSlug: 'atto-3', description: 'Semua tentang BYD Atto 3: harga, spesifikasi, dan pengalaman berkendara.' },
  { slug: 'seal', label: 'BYD Seal', modelSlug: 'seal', description: 'Semua tentang BYD Seal: performa, fitur, dan perbandingan.' },
  { slug: 'm6', label: 'BYD M6', modelSlug: 'm6', description: 'Semua tentang BYD M6: MPV listrik keluarga, harga, dan fitur.' },
  { slug: 'm6dm', label: 'BYD M6 DM-i', modelSlug: 'sealion-6-dm-i', description: 'Semua tentang BYD M6 DM-i: MPV plug-in hybrid untuk keluarga.' },
  { slug: 'dolphin', label: 'BYD Dolphin', modelSlug: 'dolphin', description: 'Semua tentang BYD Dolphin: hatchback listrik perkotaan.' },
  { slug: 'sealion-7', label: 'BYD Sealion 7', modelSlug: 'sealion-7', description: 'Semua tentang BYD Sealion 7: SUV listrik premium.' },
  { slug: 'insight', label: 'Insight', description: 'Panduan mobil listrik: charging, pajak, perawatan, dan tips.' },
  { slug: 'promo', label: 'Promo', description: 'Promo dan program penjualan terbaru dealer BYD Asia Afrika Bandung.' },
];

export const topicBySlug = (slug: string) => topics.find((t) => t.slug === slug);

export interface ArticleFaq {
  question: string;
  answer: string;
}

export interface Article {
  slug: string;
  title: string;
  metaTitle?: string;
  categories: string[];
  date: string;
  excerpt: string;
  metaDesc?: string;
  focusKeyword?: string;
  content?: string;
  body?: string[];
  faqs?: ArticleFaq[];
  image?: string;
  draft?: boolean;
}

export const articles: Article[] = [
  {
    slug: 'berapa-km-byd-atto-1',
    title: 'Berapa KM Jarak Tempuh BYD Atto 1? (contoh)',
    categories: ['atto-1'],
    date: '2026-11-10',
    excerpt: 'Contoh artikel topic cluster untuk model Atto 1.',
    body: ['TODO: tulis isi artikel.'],
    draft: true,
  },
];

export const primaryCategory = (a: Article) =>
  Array.isArray(a.categories) && a.categories.length > 0 ? a.categories[0] : 'insight';

export const publishedArticles = () =>
  articles
    .filter((a) => !a.draft || import.meta.env.DEV)
    .sort((a, b) => b.date.localeCompare(a.date));

export const articlesByTopic = (topicSlug: string) =>
  publishedArticles().filter((a) => Array.isArray(a.categories) && a.categories.includes(topicSlug));

export const formatTanggal = (iso: string) =>
  new Intl.DateTimeFormat('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }).format(new Date(iso));
