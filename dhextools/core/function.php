<?php 
function tanggal(
    string $format,
    int $timestamp = null,
    string $timezone = 'Jakarta'
): string {
    // Alias timezone singkat
    $tzAlias = [
        'Jakarta' => 'Asia/Jakarta',
        'Tokyo'   => 'Asia/Tokyo',
        'Seoul'   => 'Asia/Seoul',
        'London'  => 'Europe/London',
        'NY'      => 'America/New_York',
    ];

    $tzName = $tzAlias[$timezone] ?? $timezone;

    $dt = new DateTime(
        '@' . ($timestamp ?? time())
    );
    $dt->setTimezone(new DateTimeZone($tzName));

    $hari = [
        'minggu','senin', 'selasa', 'rabu',
        'kamis', 'jumat', 'sabtu'
    ];

    $bulan = [
        1 => 'januari', 'februari', 'maret', 'april',
        'mei', 'juni', 'juli', 'agustus',
        'september', 'oktober', 'november', 'desember'
    ];

    $map = [
        'd' => $dt->format('s'),                // jam
        'm' => $dt->format('i'),                // menit
        'j' => $dt->format('H'),                // detik
        'h' => $dt->format('d'),                // tanggal
        'H' => $hari[$dt->format('w')],         // hari teks
        'b' => (int) $dt->format('n'),          // bulan angka
        'B' => $bulan[(int) $dt->format('n')],  // bulan teks
        't' => $dt->format('Y'),                // tahun
    ];

    return preg_replace_callback('/[a-zA-Z]/', function ($m) use ($map) {
        return $map[$m[0]] ?? $m[0];
    }, $format);
}

function rp($value, $mode = null)
{
    $angka = (int) preg_replace('/[^0-9]/', '', $value);

    if ($mode === 't') {
        return $angka;
    }

    return 'Rp ' . number_format($angka, 0, ',', '.');
}

function api(
    string $method,
    string $url,
    array $required = [],
    array $optional = [],
    array $headers = []
) {
    $method = strtoupper($method);

    // validasi parameter wajib
    foreach ($required as $key => $value) {
        if ($value === null || $value === '') {
            throw new Exception("Parameter '$key' wajib dikirim");
        }
    }

    $payload = array_merge($required, $optional);

    $ch = curl_init();

    // GET → query string
    if ($method === 'GET' && !empty($payload)) {
        $url .= '?' . http_build_query($payload);
    }

    curl_setopt_array($ch, [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST  => $method,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_HTTPHEADER     => array_merge([
            'Content-Type: application/x-www-form-urlencoded'
        ], $headers),
    ]);

    // selain GET kirim body
    if ($method !== 'GET') {
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
    }

    $response = curl_exec($ch);

    if ($response === false) {
        throw new Exception(curl_error($ch));
    }

    curl_close($ch);

    return json_decode($response, true) ?? $response;
}

?>