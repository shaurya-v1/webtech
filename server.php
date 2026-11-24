<?php
$receiptMessage = "";
$receiptReady = false;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $orderSummary = isset($_POST['order_summary']) ? $_POST['order_summary'] : "";
    $subtotal = isset($_POST['subtotal']) ? $_POST['subtotal'] : "Rs0.00";
    $gst = isset($_POST['gst']) ? $_POST['gst'] : "Rs0.00";
    $total = isset($_POST['total']) ? $_POST['total'] : "Rs0.00";

    // 1. Save TXT receipt
    $txtContent = "BookMyTable - Order Receipt\n";
    $txtContent = $txtContent . "===========================\n";
    $txtContent = $txtContent . $orderSummary . "\n";
    $txtContent = $txtContent . "Subtotal: " . $subtotal . "\n";
    $txtContent = $txtContent . "GST (10%): " . $gst . "\n";
    $txtContent = $txtContent . "Total Payable: " . $total . "\n";

    $txtFile = fopen("receipt.txt", "w");
    if ($txtFile) {
        fwrite($txtFile, $txtContent);
        fclose($txtFile);
    }

    // 2. Save JSON receipt
    $lines = explode("\n", trim($orderSummary));
    $jsonLines = "";
    for ($i = 0; $i < count($lines); $i++) {
        $cleanLine = str_replace('"', '\"', trim($lines[$i]));
        if ($i > 0) {
            $jsonLines = $jsonLines . ",\n    ";
        }
        $jsonLines = $jsonLines . '"' . $cleanLine . '"';
    }

    $jsonContent = "{\n";
    $jsonContent = $jsonContent . '  "restaurant": "BookMyTable",' . "\n";
    $jsonContent = $jsonContent . '  "details": [' . "\n    " . $jsonLines . "\n  ],\n";
    $jsonContent = $jsonContent . '  "subtotal": "' . str_replace('"', '\"', $subtotal) . '",' . "\n";
    $jsonContent = $jsonContent . '  "gst": "' . str_replace('"', '\"', $gst) . '",' . "\n";
    $jsonContent = $jsonContent . '  "total_payable": "' . str_replace('"', '\"', $total) . '"' . "\n";
    $jsonContent = $jsonContent . "}\n";

    $jsonFile = fopen("receipt.json", "w");
    if ($jsonFile) {
        fwrite($jsonFile, $jsonContent);
        fclose($jsonFile);
    }

    // 3. Save XML receipt
    $xmlLines = "";
    for ($i = 0; $i < count($lines); $i++) {
        $safeLine = htmlspecialchars(trim($lines[$i]), ENT_XML1, 'UTF-8');
        $xmlLines = $xmlLines . "    <line>" . $safeLine . "</line>\n";
    }

    $xmlContent = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xmlContent = $xmlContent . "<receipt>\n";
    $xmlContent = $xmlContent . "  <restaurant>BookMyTable</restaurant>\n";
    $xmlContent = $xmlContent . "  <details>\n" . $xmlLines . "  </details>\n";
    $xmlContent = $xmlContent . "  <subtotal>" . htmlspecialchars($subtotal, ENT_XML1, 'UTF-8') . "</subtotal>\n";
    $xmlContent = $xmlContent . "  <gst>" . htmlspecialchars($gst, ENT_XML1, 'UTF-8') . "</gst>\n";
    $xmlContent = $xmlContent . "  <total_payable>" . htmlspecialchars($total, ENT_XML1, 'UTF-8') . "</total_payable>\n";
    $xmlContent = $xmlContent . "</receipt>\n";

    $xmlFile = fopen("receipt.xml", "w");
    if ($xmlFile) {
        fwrite($xmlFile, $xmlContent);
        fclose($xmlFile);
    }

    if ($txtFile && $jsonFile && $xmlFile) {
        $receiptReady = true;
        $receiptMessage = "Receipt saved to receipt.txt, receipt.json, and receipt.xml";
    } else {
        $receiptMessage = "Could not create one or more receipt files.";
    }
}
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>BookMyTable - Receipt</title>
        <link rel="stylesheet" href="style.css" />
    </head>
    <body>
        <header>
            <h1>BookMyTable</h1>
        </header>

        <nav>
            <ul>
                <li><a href="index.html">Login</a></li>
                <li><a href="reserve.html">Reserve Table</a></li>
                <li><a href="menu.html">Menu</a></li>
                <li><a href="checkout.html">Checkout</a></li>
            </ul>
        </nav>

        <main>
            <section class="center-text">
                <h2>Order Receipt</h2>
                <p><?php echo htmlspecialchars($receiptMessage); ?></p>
                <?php if ($receiptReady) { ?>
                    <p>
                        <a href="receipt.txt" class="btn">Open receipt.txt</a>
                        <a href="receipt.json" class="btn">Open receipt.json</a>
                        <a href="receipt.xml" class="btn">Open receipt.xml</a>
                    </p>
                <?php } ?>
                <p>
                    <a href="checkout.html" class="btn">Back to Checkout</a>
                    <a href="index.html" class="btn">Back to Home</a>
                </p>
            </section>
        </main>

        <footer>
            <p>© 2026 BookMyTable. All rights reserved.</p>
        </footer>
    </body>
</html>
