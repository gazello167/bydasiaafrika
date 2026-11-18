        </main>
    </div>
    
    <script>
        async function logout() {
            if (!confirm('Yakin ingin logout?')) return;
            
            try {
                await fetch('/admin/api/auth.php', { method: 'DELETE' });
                window.location.href = '/admin/index.php';
            } catch (err) {
                alert('Gagal logout');
            }
        }
        
        async function rebuild() {
            if (!confirm('Rebuild site? Proses ini akan memakan waktu 1-2 menit.')) return;
            
            const btn = event.target;
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.textContent = '⏳ Building...';
            
            try {
                const res = await fetch('/admin/api/rebuild.php', { method: 'POST' });
                const data = await res.json();
                
                if (data.success) {
                    alert('✅ Build berhasil! Site sudah terupdate.');
                } else {
                    alert('❌ Build gagal. Cek console atau log server.');
                    console.error(data.output);
                }
            } catch (err) {
                alert('❌ Error: ' + err.message);
            } finally {
                btn.disabled = false;
                btn.textContent = originalText;
            }
        }
    </script>
</body>
</html>
