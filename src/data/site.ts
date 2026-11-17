// Data bisnis: SATU sumber untuk NAP (Nama, Alamat, Telepon).
// Pastikan sama persis dengan Google Business Profile.
// Semua nilai bertanda TODO wajib diganti sebelum go-live.

export const site = {
  url: 'https://bydasiaafrika.com',
  name: 'BYD Asia Afrika Bandung', // TODO: sesuaikan nama dealer resmi
  shortName: 'BYD Asia Afrika',
  tagline: 'Dealer BYD di Jl. Asia Afrika No.154, Bandung 40261',
  description:
    'Dealer BYD di Jl. Asia Afrika No.154, Bandung. Test drive, simulasi kredit, dan info harga mobil listrik BYD.',
  phone: '0811-2200-467', // TODO
  whatsapp: '628112200467', // TODO: format internasional tanpa + dan 0 depan
  email: 'info@bydasiaafrika.com', // TODO
  address: {
    street: 'Jl. Asia Afrika No. 154', // TODO
    city: 'Bandung',
    region: 'Jawa Barat',
    postalCode: '40261', // TODO
    country: 'ID',
  },
  geo: { lat: -6.9222, lng: 107.6151 }, // TODO: koordinat showroom sebenarnya
  openingHours: [
    { days: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'], opens: '09:00', closes: '18:00' },
    { days: ['Sunday'], opens: '10:00', closes: '16:00' },
  ],
  mapsEmbedUrl: 'https://maps.app.goo.gl/EmLrpbP3Dvre3NQh7', // TODO: URL embed Google Maps
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
