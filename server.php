<?php
$receiptMessage = "";
$receiptReady = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $orderSummary = isset($_POST["order_summary"])
        ? $_POST["order_summary"]
        : "";
    $subtotal = isset($_POST["subtotal"]) ? $_POST["subtotal"] : "Rs0.00";
    $gst = isset($_POST["gst"]) ? $_POST["gst"] : "Rs0.00";
    $total = isset($_POST["total"]) ? $_POST["total"] : "Rs0.00";

    $content = "BookMyTable - Order Receipt\n";
    $content = $content . "===========================\n";
    $content = $content . $orderSummary . "\n";
    $content = $content . "Subtotal: " . $subtotal . "\n";
    $content = $content . "GST (10%): " . $gst . "\n";
    $content = $content . "Total Payable: " . $total . "\n";

    $file = fopen("receipt.txt", "w");
    if ($file) {
        fwrite($file, $content);
        fclose($file);
        $receiptReady = true;
        $receiptMessage = "Receipt saved to receipt.txt";
    } else {
        $receiptMessage = "Could not create receipt.txt";
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
                <?php if ($receiptReady && file_exists("receipt.txt")) { ?>
                    <p>
                        <a href="receipt.txt" class="btn">Open receipt.txt</a>
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
