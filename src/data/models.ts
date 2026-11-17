// Data model mobil. Harga & spesifikasi di bawah adalah PLACEHOLDER.
// Ganti dengan pricelist resmi sebelum publish. Satu file ini cukup untuk update.

export interface CarModel {
  slug: string;
  name: string;
  type: string; // SUV, Sedan, Hatchback, MPV
  tagline: string;
  hargaOTR: number | null; // Rupiah, null = tampilkan "Hubungi kami"
  specs: { label: string; value: string }[];
  highlights: string[];
  image?: string; // nama file di src/assets/models (opsional)
}

export const models: CarModel[] = [
  {
    slug: 'atto-3',
    name: 'BYD Atto 3',
    type: 'SUV Listrik',
    tagline: 'SUV listrik kompak untuk keluarga.',
    hargaOTR: null, // TODO
    specs: [
      { label: 'Tipe', value: 'SUV Listrik' }, // TODO isi spesifikasi resmi
      { label: 'Kapasitas baterai', value: 'TODO' },
      { label: 'Jarak tempuh', value: 'TODO' },
      { label: 'Kapasitas penumpang', value: 'TODO' },
    ],
    highlights: ['Highlight 1 (TODO)', 'Highlight 2 (TODO)', 'Highlight 3 (TODO)'],
  },
  {
    slug: 'seal',
    name: 'BYD Seal',
    type: 'Sedan Listrik',
    tagline: 'Sedan listrik performa tinggi.',
    hargaOTR: null,
    specs: [
      { label: 'Tipe', value: 'Sedan Listrik' },
      { label: 'Kapasitas baterai', value: 'TODO' },
      { label: 'Jarak tempuh', value: 'TODO' },
      { label: 'Kapasitas penumpang', value: 'TODO' },
    ],
    highlights: ['Highlight 1 (TODO)', 'Highlight 2 (TODO)', 'Highlight 3 (TODO)'],
  },
  {
    slug: 'dolphin',
    name: 'BYD Dolphin',
    type: 'Hatchback Listrik',
    tagline: 'Hatchback listrik lincah untuk perkotaan.',
    hargaOTR: null,
    specs: [
      { label: 'Tipe', value: 'Hatchback Listrik' },
      { label: 'Kapasitas baterai', value: 'TODO' },
      { label: 'Jarak tempuh', value: 'TODO' },
      { label: 'Kapasitas penumpang', value: 'TODO' },
    ],
    highlights: ['Highlight 1 (TODO)', 'Highlight 2 (TODO)', 'Highlight 3 (TODO)'],
  },
];

export const formatRupiah = (n: number) =>
  new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(n);
