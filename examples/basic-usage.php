<?php
/**
 * Basic Usage Examples for Waktu Solat API
 *
 * This file demonstrates how to use the Waktu Solat APIs programmatically.
 *
 * NOTE: Currently, api.php and apiv2.php are HTTP endpoints that output JSON.
 * These examples show HTTP-based usage.
 */

header('Content-Type: text/plain');

// Set the base URL - adjust this based on where your API is hosted
$baseUrl = 'http://localhost/waktusolat'; // Change this to your actual URL

echo "==========================================================\n";
echo "WAKTU SOLAT API - BASIC USAGE EXAMPLES\n";
echo "==========================================================\n\n";

// ========================================
// EXAMPLE 1: Get list of states
// ========================================
echo "Example 1: Getting list of Malaysian states\n";
echo "------------------------------------------------------------\n";

$statesUrl = $baseUrl . '/api.php?getStates';
$statesJson = file_get_contents($statesUrl);
$states = json_decode($statesJson, true);

echo "Available states:\n";
foreach ($states as $state) {
    echo "  - $state\n";
}
echo "\n";

// ========================================
// EXAMPLE 2: Get zones for a specific state
// ========================================
echo "Example 2: Getting zones for Selangor\n";
echo "------------------------------------------------------------\n";

$stateName = 'Selangor';
$zonesUrl = $baseUrl . '/api.php?stateName=' . urlencode($stateName);
$zonesJson = file_get_contents($zonesUrl);
$zones = json_decode($zonesJson, true);

echo "Zones in $stateName:\n";
foreach ($zones as $code => $name) {
    echo "  - $code: $name\n";
}
echo "\n";

// ========================================
// EXAMPLE 3: Get today's prayer times for a zone
// ========================================
echo "Example 3: Getting today's prayer times for SGR01 (Gombak, Petaling, etc.)\n";
echo "------------------------------------------------------------\n";

$zoneCode = 'SGR01';
$prayerTimesUrl = $baseUrl . '/api.php?zon=' . $zoneCode;
$prayerTimesJson = file_get_contents($prayerTimesUrl);
$prayerTimes = json_decode($prayerTimesJson, true);

echo "Prayer times for {$prayerTimes['nama_zon']} ({$prayerTimes['kod_zon']}):\n";
echo "Date: {$prayerTimes['tarikh_gregory']} ({$prayerTimes['hari']})\n";
echo "Hijri: {$prayerTimes['tarikh_hijrah']}\n";
echo "\n";
echo "  Imsak   : {$prayerTimes['waktu_imsak']}\n";
echo "  Subuh   : {$prayerTimes['waktu_subuh']}\n";
echo "  Syuruk  : {$prayerTimes['waktu_syuruk']}\n";
echo "  Zohor   : {$prayerTimes['waktu_zohor']}\n";
echo "  Asar    : {$prayerTimes['waktu_asar']}\n";
echo "  Maghrib : {$prayerTimes['waktu_maghrib']}\n";
echo "  Isyak   : {$prayerTimes['waktu_isyak']}\n";
echo "\n";

// ========================================
// EXAMPLE 4: Get monthly prayer times (API v2)
// ========================================
echo "Example 4: Getting prayer times for entire month (January 2025)\n";
echo "------------------------------------------------------------\n";

$zoneCode = 'SGR01';
$year = 2025;
$month = 1;
$monthlyUrl = $baseUrl . '/apiv2.php?zon=' . $zoneCode . '&tahun=' . $year . '&bulan=' . $month;
$monthlyJson = file_get_contents($monthlyUrl);
$monthlyData = json_decode($monthlyJson, true);

echo "Monthly prayer times for zone $zoneCode - January $year:\n";
echo "Total days: " . count($monthlyData['data']) . "\n\n";

// Show first 5 days as example
echo "First 5 days:\n";
for ($i = 0; $i < min(5, count($monthlyData['data'])); $i++) {
    $day = $monthlyData['data'][$i];
    echo "  {$day['tarikh']}: Subuh {$day['waktu_subuh']}, Zohor {$day['waktu_zohor']}, Maghrib {$day['waktu_maghrib']}\n";
}
echo "\n";

// ========================================
// EXAMPLE 5: Get yearly prayer times (API v2)
// ========================================
echo "Example 5: Getting prayer times for entire year (2025)\n";
echo "------------------------------------------------------------\n";

$yearlyUrl = $baseUrl . '/apiv2.php?zon=' . $zoneCode . '&tahun=' . $year;
$yearlyJson = file_get_contents($yearlyUrl);
$yearlyData = json_decode($yearlyJson, true);

echo "Yearly prayer times for zone $zoneCode - $year:\n";
echo "Months returned: " . count($yearlyData) . "\n";
echo "Each month contains daily prayer times.\n\n";

// Show total days across all months
$totalDays = 0;
foreach ($yearlyData as $monthIndex => $monthData) {
    if (isset($monthData['data'])) {
        $totalDays += count($monthData['data']);
    }
}
echo "Total days of prayer times: $totalDays\n";
echo "\n";

// ========================================
// EXAMPLE 6: Error handling
// ========================================
echo "Example 6: Error handling for invalid zone\n";
echo "------------------------------------------------------------\n";

$invalidZone = 'INVALID123';
$errorUrl = $baseUrl . '/api.php?zon=' . $invalidZone;
$errorJson = @file_get_contents($errorUrl);

if ($errorJson === false) {
    echo "Error: Could not fetch data (network error or invalid URL)\n";
} else {
    $errorData = json_decode($errorJson, true);
    if (isset($errorData['error'])) {
        echo "API Error: {$errorData['error']}\n";
    } else {
        echo "Unexpected response format\n";
    }
}
echo "\n";
