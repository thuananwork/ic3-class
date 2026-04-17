<?php
$tenLop = file_exists('active_class.txt') ? trim(file_get_contents('active_class.txt')) : header("Location: index.php");
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Lớp <?php echo htmlspecialchars($tenLop); ?></title>
    <style>
        :root {
            --primary: #3498db;
            --success: #27ae60;
            --danger: #e74c3c;
            --dark: #2c3e50;
            --soft-black: #2d3a4f;
            --student-name: #1e2a44;
            --orange: #e67e22;
            --soft-blue: #e3f2fd;
            --radius: 12px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            display: flex;
            height: 100vh;
            overflow: hidden;
            color: #333;
        }

        /* ================== SIDEBAR ================== */
        .sidebar {
            width: 380px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.05);
            z-index: 10;
            margin: 15px;
            border-radius: var(--radius);
        }

        .sidebar-header {
            padding: 20px;
            background: var(--dark);
            color: white;
            text-align: center;
            border-radius: var(--radius) var(--radius) 0 0;
        }

        .sidebar-content {
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 12px;
            overflow-y: auto;
        }

        textarea {
            flex: 1;
            font-family: 'Consolas', monospace;
            font-size: 14px;
            padding: 15px;
            border: 2px solid #f1f2f6;
            border-radius: 10px;
            resize: none;
            background: #fff;
            outline: none;
        }

        .btn {
            padding: 12px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            font-size: 14px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-save {
            background: var(--success);
            color: white;
        }

        .btn-excel {
            background: #219150;
            color: white;
        }

        .btn-back {
            background: #dfe6e9;
            color: #636e72;
        }

        .btn-delete {
            background: #ff7675;
            color: white;
        }

        /* ================== MAIN CONTENT ================== */
        .main-content {
            flex: 1;
            padding: 25px 25px 25px 10px;
            overflow-y: auto;
            scroll-behavior: smooth;
        }

        .header-main {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 0 10px;
        }

        .status {
            background: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            font-weight: 600;
        }

        .table-container {
            background: white;
            border-radius: var(--radius);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: var(--primary);
            color: white;
            padding: 18px 15px;
            text-align: left;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        td {
            padding: 14px 15px;
            border-bottom: 1px solid #f1f2f6;
            font-size: 0.95rem;
        }

        tr:hover {
            background-color: #f0f7ff !important;
            cursor: pointer;
        }

        tr.selected-row {
            background-color: var(--soft-blue) !important;
            box-shadow: inset 4px 0 0 var(--primary);
        }

        /* ================== MÀU SẮC & FONT CÁC CỘT ================== */
        .pc-num {
            font-weight: 500;
            color: var(--soft-black);
            width: 80px;
        }

        .student-name {
            font-weight: 700;
            color: var(--student-name);
            font-family: 'Segoe UI', sans-serif;
            /* Font theo yêu cầu */
            font-size: 1rem;
            /* Tăng nhẹ để dễ đọc hơn */
        }

        .score-text {
            color: var(--success);
            font-weight: 700;
        }

        .max-score-cell {
            text-align: center;
            width: 110px;
        }

        .max-score-badge {
            color: var(--orange);
            font-weight: 800;
            font-size: 1.2rem;
            display: block;
        }

        .time-text {
            color: var(--soft-black);
            font-weight: 700;
            text-align: center;
            width: 130px;
        }

        .has-score {
            background-color: #fafffa;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 10px;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <div class="sidebar-header">
            <h2>LỚP <?php echo htmlspecialchars($tenLop); ?></h2>
        </div>

        <div class="sidebar-content">
            <textarea id="txtList" placeholder="Dán danh sách học sinh vào đây..."><?php
                                                                                    $pathDS = "danhsach/danhsach_$tenLop.txt";
                                                                                    if (file_exists($pathDS)) echo htmlspecialchars(file_get_contents($pathDS));
                                                                                    ?></textarea>
            <button onclick="saveList()" class="btn btn-save">💾 LƯU DANH SÁCH</button>
            <button onclick="window.location.href='export_excel.php'" class="btn btn-excel">📥 XUẤT FILE EXCEL</button>
            <a href="index.php" class="btn btn-back">⬅ CHỌN LỚP KHÁC</a>
            <button onclick="deleteScores()" class="btn btn-delete">🗑️ XÓA DỮ LIỆU ĐIỂM</button>
        </div>
    </div>

    <div class="main-content" id="mainScroll">
        <div class="header-main">
            <h1 style="color: var(--dark);">🔴 BẢNG ĐIỂM TRỰC TUYẾN</h1>
            <div class="status">
                <span style="color: #2ecc71; margin-right: 5px;">●</span> Cập nhật: 5 giây/lần
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 80px;">Máy</th>
                        <th>Học Sinh</th>
                        <th style="width: 260px;">Tiến trình</th>
                        <th style="width: 110px; text-align: center;">Điểm Max</th>
                        <th style="width: 130px; text-align: center;">Thời gian</th>
                    </tr>
                </thead>
                <tbody id="tableBody"></tbody>
            </table>
        </div>
    </div>

    <script>
        let selectedPC = null;
        let currentData = {
            mapping: {},
            scores: {}
        };

        window.addEventListener('keydown', function(e) {
            if (!["ArrowUp", "ArrowDown", "Home", "End"].includes(e.key)) return;
            e.preventDefault();

            const oldSelected = selectedPC;

            if (selectedPC === null) {
                selectedPC = 1;
            } else {
                if (e.key === "ArrowDown" && selectedPC < 50) selectedPC++;
                else if (e.key === "ArrowUp" && selectedPC > 1) selectedPC--;
                else if (e.key === "Home") selectedPC = 1;
                else if (e.key === "End") selectedPC = 50;
            }

            updateSelection(oldSelected, selectedPC);
            scrollToRow(selectedPC);
        });

        function updateSelection(oldPC, newPC) {
            if (oldPC !== null) {
                const oldRow = document.getElementById(`row-pc${oldPC}`);
                if (oldRow) oldRow.classList.remove('selected-row');
            }
            if (newPC !== null) {
                const newRow = document.getElementById(`row-pc${newPC}`);
                if (newRow) newRow.classList.add('selected-row');
            }
        }

        function scrollToRow(pcNum) {
            const row = document.getElementById(`row-pc${pcNum}`);
            if (row) {
                row.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        }

        function saveList() {
            const data = document.getElementById('txtList').value;
            fetch('api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'action=save_list&data=' + encodeURIComponent(data)
            }).then(() => alert('✅ Đã lưu danh sách thành công!'));
        }

        function deleteScores() {
            if (confirm(`Xóa toàn bộ điểm lớp hiện tại?`)) {
                fetch('api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'action=delete_scores'
                }).then(() => refreshData());
            }
        }

        function selectRow(pcNum) {
            const old = selectedPC;
            selectedPC = (selectedPC === pcNum) ? null : pcNum;
            updateSelection(old, selectedPC);
        }

        function refreshData() {
            fetch('api.php?action=get_data')
                .then(res => res.json())
                .then(data => {
                    currentData = data;
                    renderTable(data);
                });
        }

        function renderTable(data) {
            let html = '';
            for (let i = 1; i <= 50; i++) {
                const name = data.mapping[i] || '';
                const scoreData = data.scores[i] || {
                    score: '',
                    max: '',
                    timeSpent: ''
                };

                let rowClass = (scoreData.score !== '') ? 'has-score' : '';
                if (selectedPC === i) rowClass += ' selected-row';

                html += `
                <tr id="row-pc${i}" class="${rowClass.trim()}" onclick="selectRow(${i})">
                    <td class="pc-num">PC${i.toString().padStart(2, '0')}</td>
                    <td class="student-name">${name}</td>
                    <td class="score-text">${scoreData.score}</td>
                    <td class="max-score-cell">
                        <span class="${scoreData.max !== '' ? 'max-score-badge' : ''}">${scoreData.max}</span>
                    </td>
                    <td class="time-text">${scoreData.timeSpent}</td>
                </tr>`;
            }
            document.getElementById('tableBody').innerHTML = html;
        }

        setInterval(refreshData, 5000);
        refreshData();
    </script>
</body>

</html>