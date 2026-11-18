// "Database" model mobil. File ini DITULIS ULANG oleh CMS PHP (admin/api/models.php)
// setiap kali ada perubahan di panel admin. Jangan edit manual di repo.
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
  // --- Detail page (semua opsional, ada fallback) ---
  deskripsi?: string;
  gallery?: string[];
  variants?: CarVariant[];
  hargaUpdate?: string;
  warna?: string[];
  eksterior?: string[];
  interior?: string[];
  teknologi?: string[];
}

// Default/fallback bila CMS belum mengisi data (placeholder).
const todoSpecs = (tipe: string) => [
  { label: 'Tipe', value: tipe },
  { label: 'Kapasitas baterai', value: 'TODO' },
  { label: 'Jarak tempuh', value: 'TODO' },
  { label: 'Kapasitas penumpang', value: 'TODO' },
];
const todoHighlights = ['Keunggulan 1 (TODO)', 'Keunggulan 2 (TODO)', 'Keunggulan 3 (TODO)'];

export const models: CarModel[] = [
  {
    slug: 'm6',
    name: 'BYD M6',
    category: 'MPV',
    powertrain: 'BEV',
    isNew: true,
    tagline: 'MPV listrik untuk keluarga besar.',
    hargaOTR: null,
    specs: todoSpecs('MPV Listrik'),
    highlights: todoHighlights,
  },
  {
    slug: 'atto-3',
    name: 'BYD Atto 3',
    category: 'SUV',
    powertrain: 'BEV',
    tagline: 'SUV listrik kompak untuk keluarga.',
    hargaOTR: null,
    specs: todoSpecs('SUV Listrik'),
    highlights: todoHighlights,
  },
  {
    slug: 'sealion-7',
    name: 'BYD Sealion 7',
    category: 'SUV',
    powertrain: 'BEV',
    tagline: 'SUV listrik premium.',
    hargaOTR: null,
    specs: todoSpecs('SUV Listrik'),
    highlights: todoHighlights,
  },
  {
    slug: 'sealion-6-dm-i',
    name: 'BYD Sealion 6 DM-i',
    category: 'SUV',
    powertrain: 'PHEV',
    tagline: 'SUV plug-in hybrid.',
    hargaOTR: null,
    specs: todoSpecs('SUV Plug-in Hybrid'),
    highlights: todoHighlights,
  },
  {
    slug: 'seal',
    name: 'BYD Seal',
    category: 'Sedan',
    powertrain: 'BEV',
    tagline: 'Sedan listrik performa tinggi.',
    hargaOTR: null,
    specs: todoSpecs('Sedan Listrik'),
    highlights: todoHighlights,
  },
  {
    slug: 'dolphin',
    name: 'BYD Dolphin',
    category: 'Citycar',
    powertrain: 'BEV',
    tagline: 'Hatchback listrik lincah untuk perkotaan.',
    hargaOTR: null,
    specs: todoSpecs('Hatchback Listrik'),
    highlights: todoHighlights,
  },
  {
    slug: 'atto-1',
    name: 'BYD Atto 1',
    category: 'Citycar',
    powertrain: 'BEV',
    isNew: true,
    tagline: 'Citycar listrik yang ringkas dan irit.',
    hargaOTR: null,
    specs: todoSpecs('Citycar Listrik'),
    highlights: todoHighlights,
  },
];

export const activeCategories = categoryOrder.filter((c) => models.some((m) => m.category === c));
export const activePowertrains = (Object.keys(powertrainLabels) as Powertrain[]).filter((p) =>
  models.some((m) => m.powertrain === p),
);

export const formatRupiah = (n: number) =>
  new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(n);
