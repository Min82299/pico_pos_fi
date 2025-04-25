<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Pixel Team</title>
    <style>
        :root {
            --main-color: #4CAF50;
            --accent-color:rgb(0, 0, 0);
            --danger-color: #DC3545;
            --bg-color:rgb(0, 0, 0);
            --btn-color: #ffffff;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            text-align: center;
            padding: 30px;
        }
        .calculator {
            display: inline-block;
            padding: 30px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 0 15px rgba(0,0,0,0.25);
            max-width: 400px;
            margin: 0 auto;
        }
        input[type="text"] {
            font-size: 24px;
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            text-align: right;
        }
        .buttons input {
            width: 80px;
            height: 60px;
            font-size: 20px;
            margin: 6px;
            border-radius: 10px;
            border: none;
            color: var(--btn-color);
            background-color: var(--accent-color);
            transition: transform 0.1s ease;
        }

        .buttons input:hover {
            background-color:rgb(60, 99, 85);
            transform: scale(1.05);
        }

        .buttons input[value="クリア"], 
        .buttons input[value="AC"] {
            background-color: var(--danger-color);
        }

        .buttons input[value="計上"] {
            background-color: var(--main-color);
        }

        .buttons input[value="税込"]{
            background-color: orange;
            color: white;
        }

        .buttons input[value="="] {
            background-color:rgb(73, 67, 73);
        }
        .result {
            margin-top: 15px;
            font-size: 18px;
        }
    </style>
</head>
<body>

<h1>🧮 Pico POS</h1>

<div class="calculator">
    <form method="POST" action="process.php" id="calcForm">
        <input type="text" name="display" id="display" readonly>

        <div class="buttons">
            <input type="button" value="7" onclick="appendNumber('7')">
            <input type="button" value="8" onclick="appendNumber('8')">
            <input type="button" value="9" onclick="appendNumber('9')">
            <input type="button" value="÷" onclick="appendOperator('/')"><br>

            <input type="button" value="4" onclick="appendNumber('4')">
            <input type="button" value="5" onclick="appendNumber('5')">
            <input type="button" value="6" onclick="appendNumber('6')">
            <input type="button" value="×" onclick="appendOperator('*')"><br>

            <input type="button" value="1" onclick="appendNumber('1')">
            <input type="button" value="2" onclick="appendNumber('2')">
            <input type="button" value="3" onclick="appendNumber('3')">
            <input type="button" value="−" onclick="appendOperator('-')"><br>

            <input type="button" value="0" onclick="appendNumber('0')">
            <input type="button" value="." onclick="appendNumber('.')">
            <input type="button" value="+" onclick="appendOperator('+')">
            <input type="button" value="=" onclick="evaluateExpression()"><br>

            <input type="button" value="AC" onclick="deleteLastChar()">
            <!-- <input type="button" value="数量" onclick="setQuantity()"> -->
            <input type="button" value="税込" onclick="checkTax()">
            <input type="submit" value="計上" onclick="calculateTax()">
            <input type="button" value="クリア" onclick="clearDisplay()">
        </div>

        <input type="hidden" name="quantity" id="quantity" value="1">
        <input type="hidden" name="taxed_total" id="taxed_total">
    </form>

    <div class="result" id="resultArea"></div>
</div>

<script>
    let currentInput = '';
    let quantity = 1;

    function appendNumber(num) {
        currentInput += num;
        document.getElementById("display").value = currentInput;
    }

    function appendOperator(op) {
        if (currentInput.length > 0 && !isNaN(currentInput.slice(-1))) {
            currentInput += op;
            document.getElementById("display").value = currentInput;
        }
    }

    function evaluateExpression() {
        try {
            const result = eval(currentInput);
            currentInput = result.toString();
            document.getElementById("display").value = currentInput;
        } catch (e) {
            alert("計算式が無効です！");
        }
    }

    function clearDisplay() {
        currentInput = '';
        document.getElementById("display").value = '';
        document.getElementById("resultArea").innerText = '';
    }

    // function setQuantity() {
    //     const q = prompt("数量を入力してください:", "1");
    //     if (!isNaN(q) && Number(q) > 0) {
    //         quantity = Number(q);
    //         document.getElementById("quantity").value = quantity;
    //         document.getElementById("resultArea").innerText = `📦 数量: ${quantity}`;
    //     } else {
    //         alert("正しい数量を入力してください。");
    //     }
    // }
    function deleteLastChar() {
        currentInput = currentInput.slice(0, -1);
        document.getElementById("display").value = currentInput;
    }

    function checkTax() {
        const price = parseFloat(currentInput);
        if (isNaN(price)) {
            alert("金額を入力してください。");
            return;
        }
        const tax = price * 0.1;
        const taxed = price + tax;
        document.getElementById("resultArea").innerText =
            `🧾 税: ${tax.toFixed(2)} 円\n合計: ${taxed.toFixed(2)} 円`;
    }

    function calculateTax() {
        const price = parseFloat(currentInput);
        if (isNaN(price)) {
            alert("金額を入力してください。");
            event.preventDefault();
            return;
        }
        const total = (price + price * 0.1) * quantity;
        document.getElementById("taxed_total").value = total.toFixed(2);
    }
</script>


    <div style="margin-top: 30px;">
        <a href="close_shift.php">
            🔐 チェックアウト（本日集計）
        </a>
    </div>
</body>

</html>