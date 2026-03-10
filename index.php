<?php
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Grab the public key, falling back to an empty string if it's missing
$checkoutPublicKey = $_SERVER['CHECKOUT_PUBLIC_KEY'] ?? $_ENV['CHECKOUT_PUBLIC_KEY'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adidas Checkout Replica</title>
    <script src="https://checkout-web-components.checkout.com/index.js"></script>
    <style>
        /* Typography and Reset */
        @import url('https://fonts.googleapis.com/css2?family=Yantramanav:wght@400;500;700&display=swap');
        /* Closest free alternative to AdihausDIN */

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Yantramanav', Helvetica, Arial, sans-serif;
        }

        body {
            background-color: #ffffff;
            color: #000;
            padding-bottom: 50px;
            font-size: 17px;
        }

        /* Header */
        header {
            border-bottom: 1px solid #e0e0e0;
            padding: 25px 10%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .top-bar {
            background: #000;
            color: #fff;
            text-align: center;
            font-size: 11px;
            font-weight: normal;
            letter-spacing: 1px;
            padding: 8px;
            display: flex;
            justify-content: space-around;
            text-transform: uppercase;
        }

        .logo {
            width: 60px;
        }

        .header-help {
            font-size: 16px;
        }

        .header-help a {
            color: #000;
            text-decoration: underline;
            font-weight: normal;
        }

        /* Container */
        .container {
            max-width: 80%;
            margin: 40px auto;
            padding: 0 20px;
        }

        h2 {
            font-size: 30px;
            text-transform: uppercase;
            font-weight: 700;
            margin-bottom: 20px;
            letter-spacing: 1px;
        }

        .section-header {
            padding-top: 100px;
            display: grid;
            grid-template-columns: 50% 15% 15% 20%;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        /* Order Summary Table */
        .order-item {
            display: grid;
            grid-template-columns: 15% 35% 15% 15% 20%;
            align-items: center;
            margin-bottom: 20px;
        }

        .order-item img {
            width: 90%;
            padding: 0 10px;
        }

        .item-details {
            padding-left: 20px;
            font-size: 16px;
            line-height: 1.5;
        }

        .item-details strong {
            font-size: 14px;
            text-transform: uppercase;
            display: block;
            margin-bottom: 5px;
        }

        .price-col {
            text-align: right;
            font-size: 16px;
        }

        .price-col .strikethrough {
            text-decoration: line-through;
            color: #767677;
        }

        .totals-row {
            text-align: right;
            margin-bottom: 5px;
            font-size: 16px;
            font-weight: normal;
        }

        .totals-row.main-total {
            font-size: 14px;
            margin-top: 10px;
            margin-bottom: 40px;
        }

        /* Split Layouts */
        .grid-50-50 {
            margin-top: 40px;
            display: grid;
            grid-template-columns: 50% 50%;
            gap: 40px;
            margin-bottom: 40px;
        }

        /* Form Inputs */
        .form-row {
            display: grid;
            grid-template-columns: 120px 1fr;
            align-items: center;
            margin-bottom: 15px;
        }

        .form-label {
            font-size: 16px;
            color: #000;
        }

        .form-label span {
            color: #e32b2b;
        }

        /* Red asterisk */
        .form-input {
            padding: 12px;
            border: 1px solid #e0e0e0;
            font-size: 14px;
            width: 80%;
            margin-left: 20%;
            outline: none;
            transition: border 0.2s;
        }

        .form-input:focus {
            border-color: #000;
        }

        select.form-input {
            appearance: none;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="10" height="5" viewBox="0 0 10 5"><path fill="%23000" d="M0 0l5 5 5-5z"/></svg>') no-repeat right 15px center;
            background-size: 10px;
        }

        .phone-group {
            display: grid;
            grid-template-columns: 80px 1fr;
            gap: 10px;
            margin-left: 20%;
            /* Applies your offset to the whole group */
            width: 80%;
            /* Applies your width to the whole group */
        }

        /* 2. Reset the inputs INSIDE the phone group */
        .phone-group .form-input {
            margin-left: 0;
            /* Removes the double-margin */
            width: 100%;
            /* Lets the grid handle the widths */
        }

        /* Delivery & Shipping Radios */
        .radio-row {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .radio-row input[type="radio"] {
            appearance: none;
            width: 20px;
            height: 20px;
            border: 2px solid #000;
            border-radius: 50%;
            margin-right: 15px;
            cursor: pointer;
            position: relative;
        }

        .radio-row input[type="radio"]:checked::after {
            content: '';
            width: 10px;
            height: 10px;
            background: #000;
            border-radius: 50%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .shipping-desc {
            font-size: 16px;
            margin-left: 35px;
            color: #000;
        }

        .checkbox-row {
            display: flex;
            align-items: flex-start;
            margin: 30px 0;
            font-size: 16px;
        }

        .checkbox-row input[type="checkbox"] {
            width: 18px;
            height: 18px;
            border: 1px solid #767677;
            margin-right: 15px;
            appearance: none;
            cursor: pointer;
        }

        .checkbox-row input[type="checkbox"]:checked {
            background: #000;
            border-color: #000;
        }

        /* Payment Tabs */
        .payment-methods {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
            margin-top: 15px;
        }

        .payment-tab {
            width: 100px;
            height: 80px;
            border: 1px solid #e0e0e0;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            background: #fff;
            transition: border 0.2s;
        }

        .payment-tab.active {
            border: 2px solid #000;
        }

        payment-tab img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            /* Prevents the logo from getting stretched out of shape */
        }

        /* Keep the Visa/MC logos side-by-side cleanly */
        .payment-tab.multi-logo img {
            width: auto;
            height: 50%;
            margin: 0 3px;
        }

        .payment-content {
            display: none;
            margin-bottom: 20px;
        }

        .payment-content.active {
            display: block;
        }

        /* Billing Summary */
        .summary-border {
            border-top: 1px solid #000;
            border-bottom: 1px solid #e0e0e0;
            padding: 15px 0;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            font-weight: normal;
            font-size: 16px;
            cursor: pointer;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 16px;
            font-weight: normal;
        }

        .summary-row.total {
            border-top: 1px solid #e0e0e0;
            padding-top: 15px;
            font-size: 14px;
            margin-top: 10px;
        }

        /* Submit Button */
        .terms-text {
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .terms-text a {
            color: #000;
            text-decoration: underline;
        }

        /* .btn-checkout {
            background-color: #000;
            color: #fff;
            padding: 15px 20px;
            text-transform: uppercase;
            font-weight: normal;
            font-size: 16px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            letter-spacing: 1px;
            width: 100%;
            max-width: 250px;
            justify-content: space-between;
        }

        .btn-checkout:hover {
            background-color: #333;
        } */

        /* Flex rows for dates */
        .date-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
    </style>
</head>

<body>
    <div class="top-bar">
        <span>FREE DELIVERY OVER 250 AED</span>
        <span>TABBY: SHOP NOW & PAY LATER</span>
    </div>
    <header>
        <img src="/photos/Adidas-Logo.png" alt="Adidas Logo" class="logo">
        <div class="header-help">Need Help? <a href="#">Contact Us</a></div>
    </header>

    <div class="container">

        <div class="section-header">
            <div style="font-size: 30px; font-weight: bold;">ORDER SUMMARY</div>
            <div>Quantity</div>
            <div>Price</div>
            <div style="text-align: right;">Total</div>
        </div>

        <div class="order-item">
            <img src="https://assets.adidas.com/images/w_383,h_383,f_auto,q_auto,fl_lossy,c_fill,g_auto/4ccd0f23f55444a98666cb497701bddc_9366/handball-spezial-shoes.jpg"
                alt="Shoe">
            <div class="item-details">
                <strong>Handball Spezial Shoes</strong>
                <div>Size: 42</div>
                <div>Color: CLAY / BEIGE / GUM</div>
            </div>
            <div>1</div>
            <div>AED 499.00</div>
            <div class="price-col">
                <div>AED 349.30</div>
                <div class="strikethrough">Was: AED 499.00</div>
                <div>You Save: <strong>AED 149.70</strong></div>
            </div>
        </div>

        <div class="totals-row">Items Total &nbsp;&nbsp;&nbsp; <strong>AED 499.00</strong></div>
        <div class="totals-row main-total">Total (Excluding Delivery) &nbsp;&nbsp;&nbsp; <strong>AED 349.30</strong>
        </div>

        <div class="grid-50-50">
            <div>
                <h2 style="border-bottom: 1px solid #e0e0e0; padding-bottom: 10px;">Billing Address</h2>
                <div class="form-row">
                    <div class="form-label">First Name <span>*</span></div>
                    <input type="text" class="form-input" placeholder="First Name">
                </div>
                <div class="form-row">
                    <div class="form-label">Last Name <span>*</span></div>
                    <input type="text" class="form-input" placeholder="Last Name">
                </div>
                <div class="form-row">
                    <div class="form-label">Email <span>*</span></div>
                    <input type="email" class="form-input" placeholder="Email">
                </div>
                <div class="form-row">
                    <div class="form-label">Country <span>*</span></div>
                    <select class="form-input">
                        <option>United Arab Emirates</option>
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-label">Address Line 1 <span>*</span></div>
                    <input type="text" class="form-input" placeholder="House number and Street">
                </div>
                <div class="form-row">
                    <div class="form-label">Address Line 2 <span>*</span></div>
                    <input type="text" class="form-input" placeholder="Zone, Apartment, Suite, Floor">
                </div>
                <div class="form-row">
                    <div class="form-label">City / Suburb <span>*</span></div>
                    <select class="form-input">
                        <option>Select City</option>
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-label">Mobile Phone <span>*</span></div>

                    <div class="phone-group">
                        <select class="form-input">
                            <option>+971</option>
                        </select>
                        <input type="text" class="form-input" placeholder="e.g. 501234567">
                    </div>

                </div>
            </div>

            <div>
                <h2 style="border-bottom: 1px solid #e0e0e0; padding-bottom: 10px;">Delivery Address</h2>
                <label class="radio-row">
                    <input type="radio" name="delivery" checked> Default (same as billing address)
                </label>
                <label class="radio-row">
                    <input type="radio" name="delivery"> Add an alternative delivery address
                </label>
            </div>
        </div>

        <div class="checkbox-row">
            <input type="checkbox">
            <div style="width: 47%;">I agree to receive information, special offers and promotions from adidas. adidas
                will process your personal data according to the Privacy Policy.</div>
        </div>

        <h2 style="border-bottom: 1px solid #e0e0e0; padding-bottom: 10px;">Shipping Method</h2>
        <div style="margin-bottom: 40px; margin-top: 20px;">
            <label class="radio-row" style="margin-bottom: 5px;">
                <input type="radio" name="shipping" checked> Free &nbsp;&nbsp;&nbsp; Standard Courier 1 to 2 business
                days
            </label>
            <div class="shipping-desc">Delivery delays expected for shipments to your country. Dubai: Next day delivery
                on week days.</div>
        </div>

        <div class="grid-50-50">
            <div>
                <h2>Payment</h2>
                <div style="font-size: 16px;">Please choose your payment method</div>

                <div class="payment-methods">
                    <div class="payment-tab active" data-target="flow-content">
                        <img src="/photos/VISA-logo.png" style="width:30%; margin-right:5px;">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/1200px-Mastercard-logo.svg.png"
                            style="width:30%">
                    </div>
                    <div class="payment-tab" data-target="paypal-content">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" style="width:80%;">
                    </div>
                    <div class="payment-tab" data-target="tabby-content">
                        <img src="/photos/tabby-badge.png" style="width:80%;">
                    </div>
                    <div class="payment-tab" data-target="apay-content">
                        <img src="/photos/apple-pay-og-twitter.jpg" style="width:80%;">
                    </div>
                    <div class="payment-tab" data-target="gpay-content">
                        <img src="/photos/google-pay-mark.png" style="width:80%;">
                    </div>
                </div>

                <div id="flow-content" class="payment-content active">
                    <div id="card-container" style="margin-bottom: 20px;"></div>

                    <!-- <button id="btn-checkout">
                        Pay and Place Order <span>→</span>
                    </button> -->
                </div>

                <div id="paypal-content" class="payment-content">
                    <p style="font-size:13px; color:#666; margin-bottom: 20px;">
                        You will be redirected to PayPal to complete your purchase securely.
                    </p>
                    <div id="paypal-button-container"></div>
                </div>

                <div id="tabby-content" class="payment-content">
                    <p style="font-size:13px; color:#666; margin-bottom: 20px;">
                        Split your purchase into 4 interest-free payments.
                    </p>
                    <div id="tabbyCard"></div>
                </div>

                <div id="gpay-content" class="payment-content">
                    <div id="gpay-container"></div>
                </div>

                <div id="apay-content" class="payment-content">
                    <div id="applepay-container"></div>
                </div>

                <div class="terms-text" style="margin-top: 25px;">
                    By clicking on 'Pay and Place Order' (or equivalent wallet button), you agree (i) to make your
                    purchase from Global-e as merchant
                    of record for this transaction, subject to Global-e's <a href="#">Terms of Sale</a>; (ii) that your
                    information will be handled by Global-e in accordance with the Global-e <a href="#">Privacy
                        Policy</a>; and (iii) that Global-e will share your information (excluding the payment details)
                    with Adidas UAE.
                </div>
            </div>
            <div>
                <h2>Billing Summary</h2>
                <div class="summary-border">
                    <span>PROMO CODE</span>
                    <svg width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 1L6 6L11 1" stroke="black" stroke-width="2" />
                    </svg>
                </div>

                <div class="summary-row">
                    <span>ITEMS TOTAL</span>
                    <span>AED 499.00</span>
                </div>
                <div class="summary-row">
                    <span>DISCOUNTS & PROMOTIONS</span>
                    <span>AED -149.70</span>
                </div>
                <div class="summary-row">
                    <span>SHIPPING</span>
                    <span>AED 0.00</span>
                </div>
                <div class="summary-row total">
                    <span>TOTAL FOR YOUR ORDER</span>
                    <span>AED 349.30</span>
                </div>
            </div>
        </div>

    </div>

    <script>
    // 1. Wrap EVERYTHING inside DOMContentLoaded
    document.addEventListener("DOMContentLoaded", () => {

        // Now we know for sure the HTML exists before grabbing it
        const tabs = document.querySelectorAll('.payment-tab');
        const contents = document.querySelectorAll('.payment-content');
        const customCheckoutBtn = document.querySelector('#btn-checkout');

        // 2. Tab Switching Logic
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                contents.forEach(c => c.classList.remove('active'));

                tab.classList.add('active');
                const targetId = tab.getAttribute('data-target');
                document.getElementById(targetId).classList.add('active');
            });
        });

        // 3. Checkout.com Initialization
        async function initCheckout() {
            try {
                const response = await fetch('/create-session.php', { method: 'POST' });
                const sessionData = await response.json();

                const translations = {
                    en: {
                        'pay_button.pay_with': 'Pay and Place Order'
                    },
                };

                const appearance = {
                    colorAction: "#000000",
                    colorBorder: "#e0e0e0",
                    colorFormBorder: "#e0e0e0",
                    borderRadius: ["1px", "1px"]
                }

                const myPublicKey = "<?php echo $checkoutPublicKey; ?>";

                const checkout = await CheckoutWebComponents({
                    paymentSession: sessionData,
                    locale: "en",
                    publicKey: myPublicKey, 
                    environment: 'sandbox',
                    translations: translations,
                    appearance: appearance,
                    componentOptions: {
                        card: {
                            showPayButton: true, 
                            displayCardholderName: 'top'
                        }
                    },
                    onReady: () => {
                        console.log('Web Components Ready');
                    },
                    onAuthorized: (_self, authorizeResult) => {
                        console.log("authorizeResult", authorizeResult);
                    },
                    onError: (component, error) => {
                        console.error("onError", error, "Component", component.type);
                    },
                    onPaymentCompleted: (_component, paymentResponse) => {
                        console.log("Create Payment with PaymentId: ", paymentResponse.id);
                    },
                });

                // Create components
                const cardComponent = checkout.create('card');
                const gpayComponent = checkout.create('googlepay');
                const applepayComponent = checkout.create('applepay');
                const paypalComponent = checkout.create('paypal');
                const tabbyComponent = checkout.create('tabby');

                // Mount components
                if (await cardComponent.isAvailable()) {
                    cardComponent.mount('#card-container');
                }
                if (await gpayComponent.isAvailable()) {
                    gpayComponent.mount('#gpay-container');
                }
                if (await applepayComponent.isAvailable()) {
                    applepayComponent.mount('#applepay-container');
                }
                if (await paypalComponent.isAvailable()) {
                    paypalComponent.mount('#paypal-button-container');
                }
                if (await tabbyComponent.isAvailable()) {
                    tabbyComponent.mount('#tabbyCard');
                }

                // 4. Bind the custom button
                // if (customCheckoutBtn) {
                //     customCheckoutBtn.addEventListener('click', (e) => {
                //         e.preventDefault();

                //         if (cardComponent.isValid()) {
                //             console.log("Submitting card payment...");
                //             cardComponent.submit();
                //         } else {
                //             console.error("Card form is invalid or incomplete.");
                //             // alert("Please complete your card details.");
                //         }
                //     });
                // }

            } catch (error) {
                console.error("Failed to initialize checkout:", error);
            }
        }

        // 5. Fire off the initialization
        initCheckout();
    });
</script>
</body>

</html>