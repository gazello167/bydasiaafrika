import type { IconName } from '../lib/icons';

export const mainNav = [
  { label: 'Home', href: '/' },
  { label: 'Model', href: '/model' },
  { label: 'Simulasi Kredit', href: '/kalkulator-kredit' },
  { label: 'Test Drive', href: '/test-drive' },
  { label: 'Berita', href: '/berita' },
  { label: 'Kontak', href: '/kontak' },
];

// Ikon layanan (grid di beranda dan di menu burger mobile)
export const quickLinks: { label: string; href: string; icon: IconName }[] = [
  { label: 'Pricelist', href: '/pricelist', icon: 'tag' },
  { label: 'E-Brosur', href: '/e-brosur', icon: 'book' },
  { label: 'Simulasi Kredit', href: '/kalkulator-kredit', icon: 'calculator' },
  { label: 'Test Drive', href: '/test-drive', icon: 'wheel' },
  { label: 'Booking Servis', href: '/booking-servis', icon: 'wrench' },
  { label: 'Trade In', href: '/trade-in', icon: 'swap' },
];
