// Fetch konten dari CMS API (build-time SSG). Jika API mati/error, kembalikan null
// agar pemanggil bisa fallback ke data lokal. Set PUBLIC_CMS_API_URL di .env.
const base = import.meta.env.PUBLIC_CMS_API_URL?.replace(/\/$/, '');

export async function cmsFetch<T>(path: string): Promise<T | null> {
  if (!base) return null;
  try {
    const res = await fetch(`${base}/${path.replace(/^\//, '')}`, {
      headers: { Accept: 'application/json' },
    });
    if (!res.ok) return null;
    return (await res.json()) as T;
  } catch {
    return null;
  }
}
