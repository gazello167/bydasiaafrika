// Slide hero. File ini DITULIS ULANG oleh CMS PHP (admin/api/slides.php)
// setiap kali ada perubahan di panel admin. Jangan edit manual di repo.
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
    eyebrow: 'Promo',
    title: 'Promo bulan ini (TODO)',
    text: 'Tulis detail promo resmi di sini, lengkap dengan periode berlaku.',
    cta: { label: 'Lihat Pricelist', href: '/pricelist' },
  },
];
