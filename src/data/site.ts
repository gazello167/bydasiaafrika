// Data bisnis: SATU sumber untuk NAP (Nama, Alamat, Telepon).
// Pastikan sama persis dengan Google Business Profile.
// Semua nilai bertanda TODO wajib diganti sebelum go-live.

export const site = {
  url: 'https://bydasiaafrika.com',
  name: 'BYD Asia Afrika Bandung', // TODO: sesuaikan nama dealer resmi
  shortName: 'BYD Harmony Bandung',
  // Logo header. Isi `src` dengan URL gambar (https://...) atau path di public/ (mis. '/logo.svg').
  // Kosongkan `src` untuk kembali ke teks. Pakai versi logo PUTIH/terang: latar header gelap.
  // `width`/`height` = ukuran asli gambar (mencegah layout bergeser); tampil setinggi 32px.
  logo: {
    src: 'https://byd.gazello.my.id/images/logo.svg', // TODO: URL logo
    width: 120,
    height: 32,
  },
  tagline: 'Dealer BYD di Jl. Asia Afrika, Bandung',
  description:
    'Dealer BYD di Jl. Asia Afrika No. 154, Bandung. Test drive, simulasi kredit, dan info harga mobil listrik BYD.',
  phone: '0811-2200-467', // TODO
  whatsapp: '628112200467', // TODO: format internasional tanpa + dan 0 depan
  email: 'bydasiaafrika@gmail.com', // TODO
  address: {
    street: 'Jl. Asia Afrika No.154', // TODO
    city: 'Bandung',
    region: 'Jawa Barat',
    postalCode: '40261', // TODO
    country: 'ID',
  },
  geo: { lat: -6.91978, lng: 107.61834 }, // TODO: koordinat showroom sebenarnya
  openingHours: [
    { days: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'], opens: '09:00', closes: '18:00' },
    { days: ['Sunday'], opens: '10:00', closes: '16:00' },
  ],
  mapsEmbedUrl: 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.7347491603246!2d107.61259237604486!3d-6.9222790677562385!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e700114a2efb%3A0x65b3bcca19feb6dc!2sBYD%20Harmony%20Asia%20Afrika!5e0!3m2!1sid!2sid!4v1794988833831!5m2!1sid!2sid', // TODO: URL embed Google Maps
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
