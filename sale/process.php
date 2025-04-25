<?php
$pdo = new PDO('mysql:host=localhost;dbname=pico_pos;charset=utf8', 'root', '');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $item = $_POST['item'];
    $unit_price = intval($_POST['unit_price']);
    $quantity = intval($_POST['quantity']);
    $total = $unit_price * $quantity;
    $receipt_no = 'R' . date("YmdHis");

    $stmt = $pdo->prepare("INSERT INTO sales (receipt_no, item_name, unit_price, quantity, total_amount) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$receipt_no, $item, $unit_price, $quantity, $total]);

    header("Location: index.php?message=success");
    exit();
}
?>
