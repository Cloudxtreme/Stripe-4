<?php
    require_once('./lib/Stripe.php');
    require_once('./config/config.php');

    if ($_POST) {
        // var_dump($_POST);
        $amount = 1500;
        $apiKey = STRIPE_PUBLIC_KEY;
        $email = $_POST['receipt_email'];
        $error = '';
        $success = '';
        $token = $_POST['stripeToken'];
        Stripe::setApiKey($apiKey);
        try {
            if (!isset($_POST['stripeToken']))
                throw new Exception('The Stripe token was not generated correctly.');
            Stripe_Charge::create(array(
                'amount' => $amount,
                'currency' => 'usd',
                'source' => $token,
                'description' => 'This was a purchase for $15.00.',
                'receipt_email' => $email,
                'metadata' => array('someShit' => 'I made Up.'))
            );
            $success = 'Your payment was successful.';
        } catch(Exception $e) {
            $error = $e->getMessage();
        }
    }
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stripe Test Form</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Stripe (PHP) example using JavaScript (no jQuery).</h1>
    <p>Test Credit Card: '<span class="yellow">4242424242424242</span>'</p>
    <p>Use '<span class="yellow">123</span>' for expirey and any future date for month/year.</p>
    <!-- to display errors returned by createToken -->
    <span id="payment-errors"><?php if (isset($error)){echo $error;} ?></span>
    <span id="payment-success"><?php if (isset($success)){echo $success;} ?></span>
    <form action="" method="POST" id="payment-form">
        <p class="help-text">( <em>Required<span>*</span></em> )</p>
        <div class="form-row">
            <label>Full Name<span>*</span></label>
            <input type="text" size="100" data-stripe="name" id="name">
        </div>
        <div class="form-row">
            <label>Address 1<span>*</span></label>
            <input type="text" size="100" data-stripe="address_line1" id="address_line1">
        </div>
        <div class="form-row">
            <label>Address 2</label>
            <input type="text" size="100" data-stripe="address_line2" id="address_line2">
        </div>
        <div class="form-row">
            <label>City<span>*</span></label>
            <input type="text" size="100" data-stripe="address_city" id="address_city">
        </div>
        <div class="form-row">
            <label>State<span>*</span></label>
            <input type="text" size="15" data-stripe="address_state" id="address_state">
        </div>
        <div class="form-row">
            <label>Postal Code<span>*</span></label>
            <input type="text" size="10" data-stripe="address_zip" id="address_zip">
        </div>
        <div class="form-row">
            <label>Email<span>*</span></label>
            <input type="text" size="254" name="receipt_email" id="receipt_email">
        </div>
        <div class="form-row">
            <label>Card Number<span>*</span></label>
            <input type="text" size="20" autocomplete="off" data-stripe="number" id="number">
        </div>

        <div class="form-row">
            <label>CVC<span>*</span></label>
            <input type="text" size="4" autocomplete="off" data-stripe="cvc" id="cvc">
            
        </div>

        <div class="form-row">
            <label>Expiration (MM/YYYY)<span>*</span></label>
            <input type="text" size="2" autocomplete="off" data-stripe="exp-month" id="exp-month">
            <span> / </span>
            <input type="text" size="4" autocomplete="off" data-stripe="exp-year" id="exp-year">
        </div>

        <button type="button" id="submit-button">Submit Payment</button>
    </form>

<script src="https://js.stripe.com/v2/"></script>
<script src="scripts/script.js"></script>
</body>
</html>