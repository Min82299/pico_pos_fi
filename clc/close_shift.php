<?php
require 'db.php';

// Lấy ngày hôm nay (giờ server)
$date = date("Y-m-d");

// Tổng hợp thu nhập, thuế và tổng tiền trong ngày
$sql = "SELECT 
            SUM(income) AS total_income,
            SUM(tax_amount) AS total_tax,
            SUM(total) AS total_all
        FROM transactions
        WHERE DATE(created_at) = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $date);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

$total_income = $data["total_income"] ?? 0;
$total_tax = $data["total_tax"] ?? 0;
$total_all = $data["total_all"] ?? 0;
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>本日売上集計</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background-color: #f4f6f8;
            text-align: center;
        }
        .summary {
            background: white;
            display: inline-block;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 10px #ccc;
        }
        .summary h2 {
            margin-bottom: 20px;
        }
        .summary p {
            font-size: 20px;
            margin: 10px 0;
        }
        a.button {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 24px;
            background-color:rgb(37, 40, 43);
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<div class="summary">
    <h2>📅 <?= $date ?> の売上集計</h2>
    <p>売上合計（税抜）: <strong><?= number_format($total_income, 2) ?> 円</strong></p>
    <p>税額合計: <strong><?= number_format($total_tax, 2) ?> 円</strong></p>
    <p>売上総額（税込）: <strong><?= number_format($total_all, 2) ?> 円</strong></p>
    <a href="index.php" class="button">⬅ レジ画面に戻る</a>
</div>

</body>
</html>