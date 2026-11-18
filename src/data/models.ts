// "Database" model mobil. Tab kategori dan filter penggerak di halaman dibuat
// otomatis dari field `category` dan `powertrain` di sini.
// SEMUA harga/spesifikasi di bawah PLACEHOLDER: samakan dengan pricelist resmi.

export type Category = 'MPV' | 'SUV' | 'Sedan' | 'Citycar';
export type Powertrain = 'BEV' | 'PHEV';

// Urutan tab kategori. Kategori tanpa model tidak ditampilkan.
export const categoryOrder: Category[] = ['MPV', 'SUV', 'Sedan', 'Citycar'];

export const powertrainLabels: Record<Powertrain, string> = {
  BEV: 'Listrik (BEV)',
  PHEV: 'Plug-in Hybrid',
};

export interface CarVariant {
  tipe: string;
  harga: number | null; // null = "Hubungi kami"
}

export interface CarModel {
  slug: string;
  name: string;
  category: Category;
  powertrain: Powertrain;
  isNew?: boolean;
  tagline: string;
  hargaOTR: number | null; // Rupiah; null = tampilkan "Hubungi kami"
  specs: { label: string; value: string }[];
  highlights: string[];
  image?: string; // path relatif ke src/assets, mis. "models/atto-3.webp"
  brosur?: string; // path PDF di public/, mis. "/brosur/atto-3.pdf"
  // --- Detail page (semua opsional, ada fallback) ---
  deskripsi?: string; // paragraf pembuka tab DESKRIPSI; fallback ke tagline
  gallery?: string[]; // gambar slider; fallback ke [image]
  variants?: CarVariant[]; // tabel harga per tipe; fallback 1 baris dari hargaOTR
  hargaUpdate?: string; // mis. "02 November 2026"
  warna?: string[];
  eksterior?: string[];
  interior?: string[];
  teknologi?: string[];
}

const todoSpecs = (tipe: string) => [
  { label: 'Tipe', value: tipe },
  { label: 'Kapasitas baterai', value: 'TODO' },
  { label: 'Jarak tempuh', value: 'TODO' },
  { label: 'Kapasitas penumpang', value: 'TODO' },
];
const todoHighlights = ['Keunggulan 1 (TODO)', 'Keunggulan 2 (TODO)', 'Keunggulan 3 (TODO)'];

// Placeholder konten tab; ganti dengan data resmi per model.
const todoList = (prefix: string) => [`${prefix} 1 (TODO)`, `${prefix} 2 (TODO)`, `${prefix} 3 (TODO)`];

import { cmsFetch } from '../lib/cms';

export const models: CarModel[] = [
  {
    slug: 'm6',
    name: 'BYD M6',
    category: 'MPV',
    powertrain: 'BEV',
    isNew: true,
    tagline: 'MPV listrik untuk keluarga besar.',
    hargaOTR: null, // TODO
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
    variants: [
      { tipe: 'Dynamic', harga: null }, // TODO
      { tipe: 'Premium', harga: null }, // TODO
      { tipe: 'Advance+', harga: null }, // TODO
    ],
    hargaUpdate: 'TODO',
    warna: todoList('Warna'),
    eksterior: todoList('Fitur eksterior'),
    interior: todoList('Fitur interior'),
    teknologi: todoList('Fitur teknologi'),
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

// Kategori & penggerak yang benar-benar dipakai data (untuk tab/filter)
const deriveCategories = (list: CarModel[]) =>
  categoryOrder.filter((c) => list.some((m) => m.category === c));
const derivePowertrains = (list: CarModel[]) =>
  (Object.keys(powertrainLabels) as Powertrain[]).filter((p) =>
    list.some((m) => m.powertrain === p),
  );

export const activeCategories = deriveCategories(models);
export const activePowertrains = derivePowertrains(models);

// Normalisasi baris DB (snake_case) ke bentuk frontend (camelCase).
// API publik mungkin mengembalikan kolom DB mentah, jadi kita terima keduanya.
function normalizeModel(raw: any): CarModel {
  const num = (v: any) => (v === null || v === undefined || v === '' ? null : Number(v));
  const arr = (v: any) => (Array.isArray(v) ? v : typeof v === 'string' ? JSON.parse(v || '[]') : []);
  return {
    slug: raw.slug,
    name: raw.name,
    category: raw.category,
    powertrain: raw.powertrain,
    isNew: Boolean(raw.isNew ?? raw.is_new),
    tagline: raw.tagline ?? '',
    hargaOTR: num(raw.hargaOTR ?? raw.harga_otr),
    specs: arr(raw.specs),
    highlights: arr(raw.highlights),
    image: raw.image ?? undefined,
    brosur: raw.brosur ?? undefined,
    deskripsi: raw.deskripsi ?? undefined,
    gallery: arr(raw.gallery).length ? arr(raw.gallery) : undefined,
    variants: arr(raw.variants).length ? arr(raw.variants) : undefined,
    hargaUpdate: raw.hargaUpdate ?? raw.harga_update ?? undefined,
    warna: arr(raw.warna).length ? arr(raw.warna) : undefined,
    eksterior: arr(raw.eksterior).length ? arr(raw.eksterior) : undefined,
    interior: arr(raw.interior).length ? arr(raw.interior) : undefined,
    teknologi: arr(raw.teknologi).length ? arr(raw.teknologi) : undefined,
  };
}

// Ambil model dari CMS; fallback ke data lokal bila API mati/belum di-set.
export async function getModels(): Promise<{
  models: CarModel[];
  activeCategories: Category[];
  activePowertrains: Powertrain[];
}> {
  const remote = await cmsFetch<any[]>('models');
  const list = remote?.length ? remote.map(normalizeModel) : models;
  return { models: list, activeCategories: deriveCategories(list), activePowertrains: derivePowertrains(list) };
}

export const formatRupiah = (n: number) =>
  new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(n);
