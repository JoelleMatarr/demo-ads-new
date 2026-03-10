<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed | Adidas Demo</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Yantramanav:wght@400;500;700&display=swap');
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Yantramanav', sans-serif; }
        body { background-color: #f4f4f4; color: #000; text-align: center; padding-top: 50px; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; padding: 60px 40px; }
        .logo { width: 80px; margin-bottom: 30px; }
        h1 { font-size: 28px; text-transform: uppercase; font-weight: 700; margin-bottom: 15px; }
        p { font-size: 16px; color: #666; margin-bottom: 30px; line-height: 1.5; }
        .order-box { background: #f4f4f4; padding: 20px; font-weight: bold; font-size: 18px; margin-bottom: 30px; }
        .btn { display: inline-block; background: #000; color: #fff; padding: 15px 30px; text-decoration: none; text-transform: uppercase; font-weight: bold; font-size: 14px; letter-spacing: 1px; }
        .btn:hover { background: #333; }
    </style>
</head>
<body>
    <div class="container">
        <img src="/photos/Adidas-Logo.png" alt="Adidas Logo" class="logo">
        <h1>Thank you for your order</h1>
        <p>We've received your payment and your order is currently being processed. You will receive an email confirmation shortly.</p>
        
        <div class="order-box">
            Payment ID: <br>
            <span style="font-size: 22px; color: #000;">
                <?php 
                    // Safely grab the payment ID from the URL
                    echo isset($_GET['cko-payment-id']) ? htmlspecialchars($_GET['cko-payment-id']) : 'N/A'; 
                ?>
            </span>
        </div>

        <a href="index.php" class="btn">Return to Shop</a>
    </div>
</body>
</html>