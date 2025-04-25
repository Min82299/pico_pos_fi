<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $income = isset($_POST["display"]) ? floatval($_POST["display"]) : 0;
    $quantity = isset($_POST["quantity"]) ? intval($_POST["quantity"]) : 1;
    $tax_rate = 10.0; // 10%
    $tax_amount = $income * $tax_rate / 100;
    $total = ($income + $tax_amount) * $quantity;

    $stmt = $conn->prepare("INSERT INTO transactions (income, tax_rate, tax_amount, total) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("dddd", $income, $tax_rate, $tax_amount, $total);
    $stmt->execute();
    $stmt->close();

    echo "<h2>✅ 登録が完了しました！</h2>";
    echo "<p>🧾 単価: {$income} 円</p>";
    // echo "<p>📦 数量: {$quantity}</p>";
    echo "<p>💸 税額: {$tax_amount} 円</p>";
    echo "<p>💰 合計: {$total} 円</p>";
    echo "<br><a href='index.php'>⬅ 戻る</a>";
} else {
    echo "無効なリクエストです。";
}
?>