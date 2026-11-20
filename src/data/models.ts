// File ini DITULIS ULANG oleh CMS (admin/api/models.php). Jangan edit manual.
export type Category = 'MPV' | 'SUV' | 'Sedan' | 'Citycar';
export type Powertrain = 'BEV' | 'PHEV';

export const categoryOrder: Category[] = ['MPV', 'SUV', 'Sedan', 'Citycar'];

export const powertrainLabels: Record<Powertrain, string> = {
  BEV: 'Listrik (BEV)',
  PHEV: 'Plug-in Hybrid',
};

export interface CarVariant {
  tipe: string;
  harga: number | null;
}

export interface FeatureItem {
  judul: string;
  gambar?: string;
  deskripsi?: string;
}

export interface CarModel {
  slug: string;
  name: string;
  category: Category;
  powertrain: Powertrain;
  isNew?: boolean;
  tagline: string;
  hargaOTR: number | null;
  specs: { label: string; value: string }[];
  highlights: string[];
  image?: string;
  brosur?: string;
  deskripsi?: string;
  metaTitle?: string;
  metaDesc?: string;
  focusKeyword?: string;
  gallery?: string[];
  variants?: CarVariant[];
  hargaUpdate?: string;
  hargaH2?: string;
  warna?: (string | FeatureItem)[];
  eksterior?: (string | FeatureItem)[];
  interior?: (string | FeatureItem)[];
  teknologi?: string[];
  cardImage?: string;
}

export const models: CarModel[] = [
  {
    slug: 'atto-1',
    name: 'BYD Atto 1',
    category: 'Citycar',
    powertrain: 'BEV',
    isNew: true,
    tagline: 'Citycar listrik yang ringkas dan irit.',
    hargaOTR: 205000000,
    specs: [],
    highlights: [],
    image: 'https://byd.gazello.my.id/models/6afd878eb2f4d-1795000206.jpg',
    brosur: 'https://byd.gazello.my.id/brosur/6afff063784c3-1795158115.pdf',
    deskripsi: 'BYD Atto 1 dilengkapi dengan sejumlah fitur penting untuk kendaraan masa kini. Pada bagian keselamatan, mobil ini memiliki airbag depan, rem cakram di keempat roda, sistem ABS (Anti-lock Braking System), EBD, dan electronic stability control. Sensor parkir belakang dan kamera mundur juga tersedia sebagai standar.\n\nSistem hiburan dilengkapi layar sentuh yang terintegrasi dengan konektivitas Bluetooth dan USB. Beberapa varian sudah mendukung koneksi Android Auto dan Apple CarPlay.',
    hargaUpdate: 'Update November 2026',
    hargaH2: 'Harga BYD Atto 1 Bandung',
    gallery: ['https://byd.gazello.my.id/wp-content/uploads/2025/08/BYD-Atto-1-activity-4.webp', 'https://byd.gazello.my.id/wp-content/uploads/2025/08/BYD-Atto-1-activity-6.webp', 'https://byd.gazello.my.id/wp-content/uploads/2025/08/BYD-Atto-1-activity-7.webp'],
    warna: [{ judul: 'Sprout Green' }, { judul: 'Apricity White' }, { judul: 'Cosmos Black' }],
    eksterior: [{ judul: 'Pencahayaan Dinamis', deskripsi: 'Lampu depan dengan desain \'Double Slash\' memberikan identitas visual kuat dan pencahayaan optimal di siang hari, dilengkapi dengan fitur automatic LED headlamp untuk kenyamanan dan keamanan.' }, { judul: 'Velg Alloy 16 Inci', deskripsi: 'Velg berukuran 16 inci melengkapi tampilan dinamis BYD Atto 1, menambah kesan sporty dan stabil.' }],
    interior: [{ judul: 'Pengisian Daya Nirkabel yang Praktis', deskripsi: 'Nikmati kemudahan pengisian daya perangkat seluler tanpa kabel, menjaga kabin tetap rapi dan tetap terhubung sepanjang perjalanan.' }],
    variants: [{ tipe: 'Premium', harga: 251000000 }, { tipe: 'Dynamic', harga: 246000000 }, { tipe: 'Standar', harga: 205000000 }],
  },
  {
    slug: 'm6',
    name: 'BYD M6',
    category: 'MPV',
    powertrain: 'BEV',
    
    tagline: 'MPV listrik untuk keluarga besar.',
    hargaOTR: null,
    specs: [],
    highlights: [],
    image: 'https://byd.gazello.my.id/models/6afdac8d72155-1795009677.jpg',
    
  },
  {
    slug: 'atto-3',
    name: 'BYD Atto 3',
    category: 'SUV',
    powertrain: 'BEV',
    
    tagline: 'SUV listrik kompak untuk keluarga.',
    hargaOTR: 421000003,
    specs: [],
    highlights: ['BYD ATTO 3 adalah mobil SUV crossover listrik, menggunakan platform BYD e-platform 3.0 dan dikenal dengan baterai Blade Battery yang diklaim aman dan tahan lama.'],
    image: 'https://byd.gazello.my.id/models/6afd666dec8f1-1794991725.webp',
    
    gallery: ['https://byd.gazello.my.id/wp-content/uploads/2025/07/byd-atto3-c.webp', 'https://byd.gazello.my.id/wp-content/uploads/2025/08/depan-atto3.webp', 'https://byd.gazello.my.id/wp-content/uploads/2025/08/byd-atto-3-interior.webp'],
  },
  {
    slug: 'm6-dm',
    name: 'BYD M6 DM',
    category: 'MPV',
    powertrain: 'PHEV',
    isNew: true,
    tagline: 'MPV plug-in hybrid.',
    hargaOTR: null,
    specs: [],
    highlights: [],
    
    
  },
  {
    slug: 'sealion-7',
    name: 'BYD Sealion 7',
    category: 'SUV',
    powertrain: 'BEV',
    
    tagline: 'SUV listrik premium.',
    hargaOTR: null,
    specs: [],
    highlights: [],
    
    
  },
  {
    slug: 'seal',
    name: 'BYD Seal',
    category: 'Sedan',
    powertrain: 'BEV',
    
    tagline: 'Sedan listrik performa tinggi.',
    hargaOTR: null,
    specs: [],
    highlights: [],
    
    
  },
  {
    slug: 'dolphin',
    name: 'BYD Dolphin',
    category: 'Citycar',
    powertrain: 'BEV',
    
    tagline: 'Hatchback listrik lincah untuk perkotaan.',
    hargaOTR: null,
    specs: [],
    highlights: [],
    
    
  },
  {
    slug: 'titan-7-dm',
    name: 'BYD TiTAN 7 DM',
    category: 'SUV',
    powertrain: 'PHEV',
    isNew: true,
    tagline: 'SUV plug-in hybrid.',
    hargaOTR: null,
    specs: [{ label: 'Tipe', value: '' }, { label: 'Kapasitas baterai', value: '' }],
    highlights: [],
    
    
  }
];

export const activeCategories = categoryOrder.filter((c) => models.some((m) => m.category === c));
export const activePowertrains = (Object.keys(powertrainLabels) as Powertrain[]).filter((p) =>
  models.some((m) => m.powertrain === p),
);

export const formatRupiah = (n: number) =>
  new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(n);
