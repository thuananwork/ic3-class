<?php
error_reporting(0);
ini_set('display_errors', 0);

$tenLop = isset($_GET['lop']) ? $_GET['lop'] : (file_exists('active_class.txt') ? trim(file_get_contents('active_class.txt')) : "");
if (empty($tenLop)) {
    die("Khong tim thay lop.");
}

// 1. Cấu hình để trình duyệt nhận diện là file Excel
$filename = "Bang_Diem_" . $tenLop . "_" . date("H-i_d-m-Y") . ".xls";
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=\"$filename\"");

// 2. Lấy dữ liệu danh sách học sinh
$danhSachHS = [];
$pathDS = "danhsach/danhsach_$tenLop.txt";
if (file_exists($pathDS)) {
    $lines = file($pathDS, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $parts = preg_split('/\s+/', trim($line));
        if (count($parts) >= 2) {
            $pcPart = array_pop($parts);
            $pcNum = (int)preg_replace('/[^0-9]/', '', $pcPart);
            $name = implode(" ", $parts);
            if ($pcNum > 0) $danhSachHS[$pcNum] = rtrim($name, " -");
        }
    }
}

// 3. Lấy dữ liệu điểm
$diemDaThu = [];
if (is_dir($tenLop)) {
    $files = glob($tenLop . "/*.html");
    foreach ($files as $file) {
        $fname = basename($file);
        $pcNum = 0;
        $score = "0";
        $timeDisplay = "0s";
        if (preg_match('/PC(\d+)_Diem_(\d+)_([\d\-]+)/i', $fname, $matches)) {
            $pcNum = (int)$matches[1];
            $score = $matches[2];
            $t = explode('-', $matches[3]);
            $timeDisplay = ((int)$t[1] > 0 ? (int)$t[1] . "p" : "") . (int)($t[2] ?? 0) . "s";
        } else {
            $content = file_get_contents($file);
            if (preg_match('/USER_NAME=([^&]*)/i', $content, $m)) {
                $pcNum = (int)preg_replace('/[^0-9]/', '', urldecode($m[1]));
            }
            if (preg_match('/[?&]sp=(\d+)/i', '&' . $content, $m)) {
                $score = $m[1];
            }
            if (preg_match('/[?&]ut=(\d+)/i', '&' . $content, $m)) {
                $sec = (int)$m[1];
                $timeDisplay = ($sec >= 60 ? floor($sec / 60) . "p" : "") . ($sec % 60) . "s";
            }
        }
        if ($pcNum >= 1 && $pcNum <= 50) {
            if (!isset($diemDaThu[$pcNum])) {
                $diemDaThu[$pcNum] = ['all' => [$score], 'max' => (int)$score, 'time' => $timeDisplay];
            } else {
                $diemDaThu[$pcNum]['all'][] = $score;
                if ((int)$score >= $diemDaThu[$pcNum]['max']) {
                    $diemDaThu[$pcNum]['max'] = (int)$score;
                    $diemDaThu[$pcNum]['time'] = $timeDisplay;
                }
            }
        }
    }
}
?>
<!-- 4. PHẦN HIỂN THỊ HTML ĐỂ EXCEL ĐỌC ĐỊNH DẠNG -->
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style>
        /* Định dạng chung */
        table {
            border-collapse: collapse;
        }

        td,
        th {
            border: 1px solid #ccc;
            /* Viền xám nhạt chuẩn Excel */
            padding: 5px;
            color: #000;
            /* Chữ màu đen */
            font-size: 13pt;
            /* KÍCH THƯỚC FONT CHỮ 13 THEO Ý THẦY */
        }

        /* Cột tiêu đề: Căn giữa, màu xanh lá nhạt mặc định */
        .header {
            background-color: #CCFFCC;
            text-align: center;
            font-weight: bold;
        }

        /* Căn giữa nội dung cho các cột cụ thể */
        .center {
            text-align: center;
        }
    </style>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th class="header" style="width: 50px;">STT</th>
                <th class="header" style="width: 250px;">Họ và tên</th>
                <th class="header" style="width: 80px;">Số máy</th>
                <th class="header" style="width: 180px;">Các lần nộp</th>
                <th class="header" style="width: 120px;">ĐIỂM CAO NHẤT</th>
                <th class="header" style="width: 120px;">Thời gian làm</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stt = 1;
            ksort($danhSachHS);
            foreach ($danhSachHS as $pc => $ten) {
                $lanNop = isset($diemDaThu[$pc]) ? implode(" | ", $diemDaThu[$pc]['all']) : "";
                $diemCao = isset($diemDaThu[$pc]) ? $diemDaThu[$pc]['max'] : "";
                $tg = isset($diemDaThu[$pc]) ? $diemDaThu[$pc]['time'] : "";

                echo "<tr>";
                echo "<td class='center'>$stt</td>";
                echo "<td>$ten</td>";
                echo "<td class='center'>PC" . str_pad($pc, 2, "0", STR_PAD_LEFT) . "</td>";
                echo "<td class='center'>$lanNop</td>";
                echo "<td class='center' style='font-weight:bold;'>$diemCao</td>";
                echo "<td class='center'>$tg</td>";
                echo "</tr>";
                $stt++;
            }
            ?>
        </tbody>
    </table>
</body>

</html>