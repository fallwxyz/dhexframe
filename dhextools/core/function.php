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
        'minggu', 'senin', 'selasa', 'rabu',
        'kamis', 'jumat', 'sabtu'
    ];

    $bulan = [
        1 => 'januari', 'februari', 'maret', 'april',
        'mei', 'juni', 'juli', 'agustus',
        'september', 'oktober', 'november', 'desember'
    ];

    $map = [
        'd' => $dt->format('H'),                // jam
        'm' => $dt->format('i'),                // menit
        'j' => $dt->format('s'),                // detik
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

?>