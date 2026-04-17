<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: text/plain");

// 1. Lấy thông tin lớp
$tenLop = file_exists('active_class.txt') ? trim(file_get_contents('active_class.txt')) : "Lop_Chua_Phan_Loai";
$ip_hs = $_SERVER['REMOTE_ADDR'];
$rawData = file_get_contents('php://input');

if ($rawData) {
    $data = urldecode($rawData);
    // Tạo thư mục lớp nếu chưa có
    if (!file_exists($tenLop)) {
        mkdir($tenLop, 0777, true);
    }

    // 2. Lấy điểm (sp)
    $diem = "0";
    if (preg_match('/[?&]sp=(\d+)/i', '&' . $data, $m)) {
        $diem = $m[1];
    }

    // 3. Lấy thời gian (ut)
    $totalSeconds = 0;
    if (preg_match('/[?&]ut=(\d+)/i', '&' . $data, $m)) {
        $totalSeconds = (int)$m[1];
    }
    $safeDuration = sprintf('%02d-%02d-%02d', floor($totalSeconds / 3600), floor(($totalSeconds % 3600) / 60), $totalSeconds % 60);

    // 4. Xác định PC
    $pcFinal = 0;
    if (file_exists("ip_mapping.txt")) {
        $lines = file("ip_mapping.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $parts = preg_split('/\s+/', trim($line));
            if (count($parts) >= 2 && $parts[0] == $ip_hs) {
                $pcFinal = (int)preg_replace('/[^0-9]/', '', $parts[1]);
                break;
            }
        }
    }
    if ($pcFinal == 0 && preg_match('/USER_NAME=([^&]*)/i', $data, $m)) {
        $pcFinal = (int)preg_replace('/[^0-9]/', '', $m[1]);
    }

    // 5. Lưu file
    $prefix = ($pcFinal >= 1 && $pcFinal <= 50) ? "PC" . str_pad($pcFinal, 2, "0", STR_PAD_LEFT) : "Khach_" . str_replace('.', '_', $ip_hs);
    $filename = $tenLop . "/" . $prefix . "_Diem_" . $diem . "_" . $safeDuration . "_" . time() . ".html";

    if (file_put_contents($filename, $rawData)) {
        echo "OK"; // iSpring cần nhận được chữ này
    } else {
        echo "Error writing file";
    }
} else {
    echo "Script san sang. Chua co du lieu iSpring gui den.";
}
