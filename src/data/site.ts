// Data bisnis: SATU sumber untuk NAP (Nama, Alamat, Telepon).
// Pastikan sama persis dengan Google Business Profile.
// Semua nilai bertanda TODO wajib diganti sebelum go-live.

export const site = {
  url: 'https://bydasiaafrika.com',
  name: 'BYD Asia Afrika Bandung', // TODO: sesuaikan nama dealer resmi
  shortName: 'BYD Asia Afrika',
  // Logo header. Isi `src` dengan URL gambar (https://...) atau path di public/ (mis. '/logo.svg').
  // Kosongkan `src` untuk kembali ke teks. Pakai versi logo PUTIH/terang: latar header gelap.
  // `width`/`height` = ukuran asli gambar (mencegah layout bergeser); tampil setinggi 32px.
  logo: {
    src: 'https://byd.gazello.my.id/wp-content/uploads/2025/07/logo.svg', // TODO: URL logo
    width: 160,
    height: 32,
  },
  tagline: 'Dealer BYD di Jl. Asia Afrika, Bandung',
  description:
    'Dealer BYD di Jl. Asia Afrika, Bandung. Test drive, simulasi kredit, dan info harga mobil listrik BYD.',
  phone: '+62-22-0000000', // TODO
  whatsapp: '6281234567890', // TODO: format internasional tanpa + dan 0 depan
  email: 'info@bydasiaafrika.com', // TODO
  address: {
    street: 'Jl. Asia Afrika No. XX', // TODO
    city: 'Bandung',
    region: 'Jawa Barat',
    postalCode: '40111', // TODO
    country: 'ID',
  },
  geo: { lat: -6.9215, lng: 107.6071 }, // TODO: koordinat showroom sebenarnya
  openingHours: [
    { days: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'], opens: '09:00', closes: '18:00' },
    { days: ['Sunday'], opens: '10:00', closes: '16:00' },
  ],
  mapsEmbedUrl: '', // TODO: URL embed Google Maps
  social: {
    instagram: '', // TODO
    tiktok: '',
    youtube: '',
  },
  // Isi jika dealer resmi, tampil di footer sebagai keterbukaan status.
  disclaimer:
    'Situs ini dikelola oleh dealer/mitra pemasaran. Harga dan promo dapat berubah sewaktu-waktu; hubungi sales untuk info terbaru.', // TODO: sesuaikan status resmi/independen
};

export const waLink = (message: string) =>
  `https://wa.me/${site.whatsapp}?text=${encodeURIComponent(message)}`;
