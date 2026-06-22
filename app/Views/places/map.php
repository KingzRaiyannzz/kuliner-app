<div id="map"></div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // ── Data marker dari PHP ──────────────
    const mapData = <?= $mapData ?>;

    // ── Inisialisasi peta ─────────────────────────────────────────────
    const map = L.map('map').setView([-6.2, 106.816666], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19,
    }).addTo(map);

    // ── Marker kustom ─────────────────────────────────────────────────
    const defaultIcon = L.divIcon({
        className: '',
        html: '<div style="background:#1D9E75;color:#fff;width:32px;height:32px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);display:flex;align-items:center;justify-content:center;border:2px solid #fff;box-shadow:0 2px 6px rgba(0,0,0,.25)"><span style="transform:rotate(45deg);font-size:14px">🍜</span></div>',
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -34],
    });

    const activeIcon = L.divIcon({
        className: '',
        html: '<div style="background:#f59e0b;color:#fff;width:38px;height:38px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);display:flex;align-items:center;justify-content:center;border:2px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,.3)"><span style="transform:rotate(45deg);font-size:18px">🍜</span></div>',
        iconSize: [38, 38],
        iconAnchor: [19, 38],
        popupAnchor: [0, -40],
    });

    // ── Buat semua marker ─────────────────────────────────────────────
    const markers = {};

    mapData.forEach(place => {
        if (!place.latitude || !place.longitude) return;

        const marker = L.marker([place.latitude, place.longitude], {
                icon: defaultIcon
            })
            .addTo(map)
            .bindPopup(`
                <div class="popup-content">
                    <div class="popup-name">${escHtml(place.name)}</div>
                    <div class="popup-addr">📍 ${escHtml(place.address)}</div>
                    <div class="popup-rating">⭐ ${parseFloat(place.avg_rating).toFixed(1)}</div>
                    <a href="/places/${place.id}" class="popup-link">Lihat Detail →</a>
                </div>
            `, { maxWidth: 220 });

        // Klik marker → scroll ke kartu terkait
        marker.on('click', () => scrollToCard(place.id));
        markers[place.id] = marker;
    });

    // ── Highlight marker saat hover kartu ─────────────────────
    function highlightMarker(id) {
        if (markers[id]) markers[id].setIcon(activeIcon);
    }

    function unhighlightMarker(id) {
        if (markers[id]) markers[id].setIcon(defaultIcon);
    }

    // ── Scroll list ke kartu dan buka popup marker ───────────────────
    function scrollToCard(id) {
        const card = document.getElementById('card-' + id);
        if (card) {
            card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            document.querySelectorAll('.place-card').forEach(c => c.classList.remove('active'));
            card.classList.add('active');
        }
        if (markers[id]) {
            markers[id].openPopup();
            map.setView(markers[id].getLatLng(), 16, { animate: true });
        }
    }

    // ── Jalankan otomatis filter saat slider rating berubah ─────────────
    document.getElementById('rating-slider').addEventListener('change', function() {
        document.getElementById('apply-filter').click();
    });

    // ── Escape HTML helper ───────────────────────────────────────────
    function escHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }
</script>