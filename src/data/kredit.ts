// Parameter kalkulator kredit. Sesuaikan dengan program leasing yang berlaku.
export const kredit = {
  dpDefault: 30, // %
  dpMin: 20,
  dpMax: 80,
  tenorOptions: [1, 2, 3, 4, 5], // tahun
  tenorDefault: 5,
  bungaFlatDefault: 5, // % per tahun, TODO ikuti program leasing
  hargaDefault: 400_000_000, // dipakai jika harga model belum diisi, TODO
  disclaimer:
    'Simulasi ini hanya estimasi dan belum termasuk asuransi, biaya administrasi, provisi, dan fidusia. Angka final mengikuti persetujuan pihak leasing.',
};
