// Konten berita dengan konsep TOPIC CLUSTER: setiap artikel punya `category`
// berupa slug topik (lihat `topics` di bawah). Artikel tentang suatu model
// masuk ke topik model tsb (money page), sisanya ke insight/promo.
//
// File ini (array `articles`) DITULIS ULANG oleh CMS PHP (admin/api/articles.php).
// Daftar `topics` statis di repo — tambah topik baru = edit di sini.
//
// Struktur URL:
//   /berita                -> grid semua topik
//   /berita/[topik]        -> daftar artikel dalam topik (mis. /berita/atto-1)
//   /berita/[topik]/[slug] -> artikel (mis. /berita/atto-1/berapa-km-byd-atto-1)

export interface Topic {
  slug: string; // dipakai sebagai `category` artikel & URL
  label: string; // nama tampilan
  description: string; // teks SEO di halaman topik
  modelSlug?: string; // jika topik = model, untuk link balik ke money page
  image?: string; // gambar kartu topik (URL R2); fallback: gambar artikel terbaru
}

// Grid halaman /berita (4 kolom desktop). Urutan = urutan tampil.
export const topics: Topic[] = [
  { slug: 'atto-1', label: 'BYD Atto 1', modelSlug: 'atto-1', description: 'Semua tentang BYD Atto 1: harga, jarak tempuh, charging, dan tips pemakaian.' },
  { slug: 'atto-3', label: 'BYD Atto 3', modelSlug: 'atto-3', description: 'Semua tentang BYD Atto 3: harga, spesifikasi, dan pengalaman berkendara.' },
  { slug: 'seal', label: 'BYD Seal', modelSlug: 'seal', description: 'Semua tentang BYD Seal: performa, fitur, dan perbandingan.' },
  { slug: 'm6', label: 'BYD M6', modelSlug: 'm6', description: 'Semua tentang BYD M6: MPV listrik keluarga, harga, dan fitur.' },
  { slug: 'm6dm', label: 'BYD M6 DM-i', description: 'Semua tentang BYD M6 DM-i: MPV plug-in hybrid untuk keluarga.' },
  { slug: 'dolphin', label: 'BYD Dolphin', modelSlug: 'dolphin', description: 'Semua tentang BYD Dolphin: hatchback listrik perkotaan.' },
  { slug: 'sealion-7', label: 'BYD Sealion 7', modelSlug: 'sealion-7', description: 'Semua tentang BYD Sealion 7: SUV listrik premium.' },
  { slug: 'insight', label: 'Insight', description: 'Panduan mobil listrik: charging, pajak, perawatan, dan tips.' },
  { slug: 'promo', label: 'Promo', description: 'Promo dan program penjualan terbaru dealer BYD Asia Afrika Bandung.' },
];

export const topicBySlug = (slug: string) => topics.find((t) => t.slug === slug);

export interface Article {
  slug: string;
  title: string;
  category: string; // slug topik
  date: string; // YYYY-MM-DD
  excerpt: string;
  body: string[];
  image?: string;
  draft?: boolean;
}

// === Data artikel (ditulis ulang oleh CMS). Di bawah ini contoh. ===
export const articles: Article[] = [
  {
    slug: 'berapa-km-byd-atto-1',
    title: 'Berapa KM Jarak Tempuh BYD Atto 1? (contoh)',
    category: 'atto-1',
    date: '2026-11-10',
    excerpt: 'Contoh artikel topic cluster untuk model Atto 1.',
    body: ['TODO: tulis isi artikel.'],
    draft: true,
  },
];

// Draft hanya tampil saat development.
export const publishedArticles = () =>
  articles
    .filter((a) => !a.draft || import.meta.env.DEV)
    .sort((a, b) => b.date.localeCompare(a.date));

export const articlesByTopic = (topicSlug: string) =>
  publishedArticles().filter((a) => a.category === topicSlug);

export const formatTanggal = (iso: string) =>
  new Intl.DateTimeFormat('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }).format(new Date(iso));
