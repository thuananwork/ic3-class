<?php
$tenLop = file_exists('active_class.txt') ? trim(file_get_contents('active_class.txt')) : "";
$folderDS = "danhsach";

if (isset($_GET['action']) && $_GET['action'] == 'get_data') {
    $danhSachMapping = [];
    $pathDS = "$folderDS/danhsach_$tenLop.txt";

    // Đọc danh sách tên học sinh từ file text
    if ($tenLop && file_exists($pathDS)) {
        $lines = explode("\n", file_get_contents($pathDS));
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            $parts = preg_split('/\s+/', $line);
            if (count($parts) >= 2) {
                $lastPart = array_pop($parts);
                $pcNum = (int)preg_replace('/[^0-9]/', '', $lastPart);
                $name = implode(" ", $parts);
                if ($pcNum > 0 && $pcNum <= 50) $danhSachMapping[$pcNum] = rtrim($name, " -");
            }
        }
    }

    $diemDaThu = [];
    if ($tenLop && is_dir($tenLop)) {
        $files = glob($tenLop . "/*.html");
        foreach ($files as $file) {
            $filename = basename($file);
            // Chỉ đọc file bắt đầu bằng PC (ví dụ PC13_Diem...)
            if (preg_match('/PC(\d+)_Diem_(\d+)_([\d\-]+)/i', $filename, $matches)) {
                $pcNum = (int)$matches[1];
                $score = $matches[2];
                $t = explode('-', $matches[3]);
                $timeDisplay = ((int)$t[1] > 0 ? (int)$t[1] . "p" : "") . (int)($t[2] ?? 0) . "s";

                if ($pcNum >= 1 && $pcNum <= 50) {
                    if (!isset($diemDaThu[$pcNum])) {
                        $diemDaThu[$pcNum] = ['score' => $score, 'max' => (int)$score, 'timeSpent' => $timeDisplay];
                    } else {
                        $diemDaThu[$pcNum]['score'] .= " | " . $score;
                        if ((int)$score >= $diemDaThu[$pcNum]['max']) {
                            $diemDaThu[$pcNum]['max'] = (int)$score;
                            $diemDaThu[$pcNum]['timeSpent'] = $timeDisplay;
                        }
                    }
                }
            }
        }
    }
    echo json_encode(['mapping' => $danhSachMapping, 'scores' => $diemDaThu]);
    exit;
}
// Các action khác giữ nguyên...

if (isset($_POST['action']) && $_POST['action'] == 'save_list') {
    file_put_contents("$folderDS/danhsach_$tenLop.txt", $_POST['data']);
    echo "Đã lưu!";
    exit;
}
if (isset($_POST['action']) && $_POST['action'] == 'delete_scores') {
    if ($tenLop && is_dir($tenLop)) {
        foreach (glob($tenLop . "/*.html") as $file) {
            if (is_file($file)) unlink($file);
        }
    }
    exit;
}
