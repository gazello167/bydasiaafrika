// Slide hero. Gambar opsional: taruh di src/assets/hero/ lalu isi image: "hero/nama.webp"
// (disarankan lebar 1920px, di bawah 200 KB). Tanpa gambar, slide memakai latar gradien.
export interface Slide {
  eyebrow: string;
  title: string;
  text: string;
  cta: { label: string; href: string };
  image?: string;
}

export const slides: Slide[] = [
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
