// Filter sisi-klien generik. Semua item tetap ada di HTML (baik untuk SEO),
// filter hanya mengatur atribut `hidden`.
//
// Markup:
//   root          : elemen pembungkus
//   [data-filter="dim"][data-value="x"] : tombol filter (value "all" = semua)
//   [data-item] data-dim="x"            : item yang difilter
//   [data-empty]                        : pesan saat tidak ada hasil
export function initFilters(root: HTMLElement) {
  const state: Record<string, string> = {};
  const buttons = Array.from(root.querySelectorAll<HTMLButtonElement>('[data-filter]'));
  const items = Array.from(root.querySelectorAll<HTMLElement>('[data-item]'));
  const empty = root.querySelector<HTMLElement>('[data-empty]');

  buttons.forEach((b) => (state[b.dataset.filter!] = 'all'));

  const apply = () => {
    let visible = 0;
    items.forEach((el) => {
      const ok = Object.entries(state).every(([dim, val]) => val === 'all' || el.dataset[dim] === val);
      el.hidden = !ok;
      if (ok) visible++;
    });
    if (empty) empty.hidden = visible > 0;
  };

  buttons.forEach((btn) => {
    btn.addEventListener('click', () => {
      const dim = btn.dataset.filter!;
      state[dim] = btn.dataset.value ?? 'all';
      buttons
        .filter((b) => b.dataset.filter === dim)
        .forEach((b) => b.setAttribute('aria-pressed', String(b === btn)));
      apply();
    });
  });
}
