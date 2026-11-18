// Konten "Discover the Latest". File ini DITULIS ULANG oleh CMS PHP
// (admin/api/articles.php) setiap kali ada perubahan di panel admin.
export type ArticleCategory = 'News & Event' | 'Insight' | 'Promo';
export const articleCategories: ArticleCategory[] = ['News & Event', 'Insight', 'Promo'];

export interface Article {
  slug: string;
  title: string;
  category: ArticleCategory;
  date: string; // YYYY-MM-DD
  excerpt: string;
  body: string[];
  image?: string;
  draft?: boolean;
}

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
];

// Draft hanya tampil saat development.
export const publishedArticles = () =>
  articles
    .filter((a) => !a.draft || import.meta.env.DEV)
    .sort((a, b) => b.date.localeCompare(a.date));

export const formatTanggal = (iso: string) =>
  new Intl.DateTimeFormat('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }).format(new Date(iso));
