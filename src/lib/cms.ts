// Fetch konten dari CMS API (build-time SSG). Jika API mati/error, kembalikan null
// agar pemanggil bisa fallback ke data lokal. Set PUBLIC_CMS_API_URL di .env.
// Mendukung dua pola URL:
//   REST : PUBLIC_CMS_API_URL=https://admin.example.com/api        -> GET /api/models
//   PHP  : PUBLIC_CMS_API_URL=https://admin.example.com/api/public -> GET /api/public/models.php
const base = import.meta.env.PUBLIC_CMS_API_URL?.replace(/\/$/, '');

async function tryFetch<T>(url: string): Promise<T | null> {
  try {
    const res = await fetch(url, { headers: { Accept: 'application/json' } });
    if (!res.ok) return null;
    const ct = res.headers.get('content-type') || '';
    if (!ct.includes('json')) return null; // tolak redirect ke halaman login (HTML)
    return (await res.json()) as T;
  } catch {
    return null;
  }
}

export async function cmsFetch<T>(path: string): Promise<T | null> {
  if (!base) return null;
  const clean = path.replace(/^\//, '');
  // Coba pola PHP dulu (CMS saat ini pakai .php), lalu pola REST.
  return (await tryFetch<T>(`${base}/${clean}.php`)) ?? (await tryFetch<T>(`${base}/${clean}`));
}
