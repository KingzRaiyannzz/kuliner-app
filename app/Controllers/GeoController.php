<?php

namespace App\Controllers;

class GeoController extends BaseController
{
    // ----------------------------------------------------------------
    // GET /geo/search?q=nama+lokasi
    // Proxy ke Nominatim API — dipanggil dari JavaScript (geocode.js)
    // Kenapa pakai proxy? Karena Nominatim butuh header User-Agent yang valid.
    // Kalau dipanggil langsung dari browser, header tidak bisa diset dengan benar.
    // ----------------------------------------------------------------
    public function search()
    {
        $query = trim($this->request->getGet('q'));

        if (empty($query)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON(['error' => 'Parameter q wajib diisi.']);
        }

        // Batas minimal 3 karakter agar tidak spam API
        if (strlen($query) < 3) {
            return $this->response->setJSON([]);
        }

        $url = 'https://nominatim.openstreetmap.org/search'
            . '?q='              . urlencode($query)
            . '&format=json'
            . '&addressdetails=1'
            . '&limit=5'
            . '&countrycodes=id';   // Batasi hasil ke Indonesia saja

        $client = \Config\Services::curlrequest();

        try {
            $response = $client->get($url, [
                'headers' => [
                    // WAJIB: Nominatim mengharuskan User-Agent yang valid
                    // Ganti dengan nama app dan email kamu yang sebenarnya
                    'User-Agent' => 'KulinerApp/1.0 (emailkamu@domain.com)',
                    'Accept'     => 'application/json',
                ],
                'timeout' => 5,
            ]);

            $data = json_decode($response->getBody(), true);

            // Format ulang response agar lebih ringkas untuk frontend
            $results = array_map(function ($item) {
                return [
                    'place_id'     => $item['place_id'],
                    'display_name' => $item['display_name'],
                    'lat'          => $item['lat'],
                    'lon'          => $item['lon'],
                    'type'         => $item['type'] ?? '',
                    'address'      => $item['address'] ?? [],
                ];
            }, $data ?? []);

            return $this->response->setJSON($results);
        } catch (\Exception $e) {
            log_message('error', 'Nominatim API error: ' . $e->getMessage());
            return $this->response
                ->setStatusCode(503)
                ->setJSON(['error' => 'Layanan geocoding tidak tersedia. Coba lagi nanti.']);
        }
    }

    // ----------------------------------------------------------------
    // GET /geo/reverse?lat=...&lon=...
    // Kebalikan: dari koordinat → nama alamat
    // Berguna kalau user klik langsung di peta
    // ----------------------------------------------------------------
    public function reverse()
    {
        $lat = $this->request->getGet('lat');
        $lon = $this->request->getGet('lon');

        if (!$lat || !$lon) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON(['error' => 'Parameter lat dan lon wajib diisi.']);
        }

        $url = 'https://nominatim.openstreetmap.org/reverse'
            . '?lat='    . urlencode($lat)
            . '&lon='    . urlencode($lon)
            . '&format=json'
            . '&zoom=18';

        $client = \Config\Services::curlrequest();

        try {
            $response = $client->get($url, [
                'headers' => [
                    'User-Agent' => 'KulinerApp/1.0 (emailkamu@domain.com)',
                ],
                'timeout' => 5,
            ]);

            $data = json_decode($response->getBody(), true);

            return $this->response->setJSON([
                'display_name' => $data['display_name'] ?? '',
                'address'      => $data['address'] ?? [],
                'lat'          => $lat,
                'lon'          => $lon,
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Nominatim reverse error: ' . $e->getMessage());
            return $this->response
                ->setStatusCode(503)
                ->setJSON(['error' => 'Layanan tidak tersedia.']);
        }
    }
}
