<?php
if (isset($_POST['class'])) {
    file_put_contents('active_class.txt', $_POST['class']);
    header("Location: quanly.php"); exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>CHỌN LỚP - THẦY AN</title>
    <style>
        body { font-family: Segoe UI, sans-serif; text-align: center; padding: 50px; background: #f0f2f5; }
        .grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; max-width: 800px; margin: auto; }
        .section { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        button { width: 100%; padding: 10px; margin: 3px 0; cursor: pointer; border: 1px solid #3498db; background: #fff; color: #3498db; font-weight: bold; border-radius: 5px; }
        button:hover { background: #3498db; color: #fff; }
    </style>
</head>
<body>
    <h1>CHỌN LỚP ĐANG DẠY</h1>
    <form method="POST" class="grid">
        <div class="section"><h3>KHỐI 6</h3><?php for($i=1;$i<=8;$i++) echo "<button name='class' value='6.$i'>6.$i</button>"; ?></div>
        <div class="section"><h3>KHỐI 7</h3><?php for($i=1;$i<=8;$i++) echo "<button name='class' value='7.$i'>7.$i</button>"; ?></div>
        <div class="section"><h3>KHỐI 8</h3><?php for($i=1;$i<=8;$i++) echo "<button name='class' value='8.$i'>8.$i</button>"; ?></div>
    </form>
</body>
</html>