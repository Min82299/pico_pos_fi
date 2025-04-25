<?php
// データベース接続
require_once '../clc/db.php';

// 検索処理
$search_date = $_GET['search_date'] ?? '';
$sql = "SELECT * FROM sales";
$params = [];

if (!empty($search_date)) {
    $sql .= " WHERE DATE(created_at) = ?";
    $params[] = $search_date;
}
$sql .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$sales = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalSales = 0;
foreach ($sales as $sale) {
    $totalSales += $sale['total_amount'];
}

// 注文リスト
$items = [
    ["name" => "コーヒー", "price" => 300],
    ["name" => "サンドイッチ", "price" => 500],
    ["name" => "ケーキ", "price" => 450],
    ["name" => "ジュース", "price" => 200],
];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Pico POS - 売上管理</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            text-align: center;
            padding: 30px;
        }
        h1 {
            color: #333;
        }
        form {
            background: white;
            padding : 20px;
            display: inline-block;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        select, input[type="number"], input[type="date"] {
            padding: 10px;
            font-size: 16px;
            margin: 10px;
        }
        input[type="submit"], .btn {
            padding: 10px 20px;
            font-size: 16px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
        }
        .btn-pay { background-color: #28a745; }
        .btn-reset { background-color:rgb(239, 143, 153); }
        .btn-back { background-color:rgb(255, 255, 255); color :#333 }
        .btn-search { background-color: #6c757d; }

        table {
            margin: 0 auto;
            border-collapse: collapse;
            width: 90%;
            background: white;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
        }
        th {
            background:rgb(88, 103, 239);
            color: white;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
    </style>
</head>
<body>
    <h1>📋 売上入力 & 一覧</h1>

    <!-- フォーム -->
    <form method="POST" action="process.php">
        <label>商品を選択：</label>
        <select name="item" required onchange="updatePrice(this)">
            <option value="">選択してください</option>
            <?php foreach ($items as $item): ?>
                <option value="<?= $item['name'] ?>" data-price="<?= $item['price'] ?>">
                    <?= $item['name'] ?> - <?= $item['price'] ?>円
                </option>
            <?php endforeach; ?>
        </select><br>

        <label>数量：</label>
        <input type="number" name="quantity" value="1" min="1" required><br>

        <input type="hidden" name="unit_price" id="unit_price">
        <input type="submit" value="💰 支払い（保存）" class="btn btn-pay">
    </form><br>

    <!-- 検索フォーム -->
    <form method="GET" style="margin-top: 20px;">
        <label>日付で検索：</label>
        <input type="date" name="search_date" value="<?= htmlspecialchars($search_date) ?>">
        <input type="submit" value="🔍 検索" class="btn btn-search">
        <a href="index.php" class="btn btn-reset">❌ クリア</a>
    </form>
    <div style="font-size: 20px; margin-bottom: 10px; color: green;">
        💰 総売上: <?= number_format($totalSales) ?> 円
    </div>
    <!-- データ表示 -->
    <h2>🧾 登録済み売上一覧</h2>
    <table>
        <thead>
            <tr>
                <th>日付</th>
                <th>レシート番号</th>
                <th>商品名</th>
                <th>単価 (円)</th>
                <th>数量</th>
                <th>合計 (円)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($sales) === 0): ?>
                <tr><td colspan="6">データがありません。</td></tr>
            <?php else: ?>
                <?php foreach ($sales as $s): ?>
                    <tr>
                        <td><?= date('Y-m-d H:i', strtotime($s['created_at'])) ?></td>
                        <td><?= htmlspecialchars($s['receipt_no']) ?></td>
                        <td><?= htmlspecialchars($s['item_name']) ?></td>
                        <td><?= $s['unit_price'] ?> 円</td>
                        <td><?= $s['quantity'] ?></td>
                        <td><?= $s['total_amount'] ?> 円</td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <br>
    <a href="index.php" class="btn btn-back">🔙 戻る</a>

    <script>
        function updatePrice(select) {
            const price = select.options[select.selectedIndex].getAttribute('data-price');
            document.getElementById("unit_price").value = price || 0;
        }
    </script>
</body>
</html>
