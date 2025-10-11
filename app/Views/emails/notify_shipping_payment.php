<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Request #<?= esc($request['id']) ?> - Wait for Purchase Invoice</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      line-height: 1.5;
      color: #333;
    }

    .header {
      background-color: #4E148C;
      color: #fff;
      padding: 15px;
      text-align: center;
    }

    .content {
      padding: 20px;
    }

    .btn {
      display: inline-block;
      padding: 5px 15px;
      background-color: #FF6600;
      color: #fff;
      text-decoration: none;
      border-radius: 4px;
    }



    .color-dot {
      display: inline-block;
      width: 12px;
      height: 12px;
      border-radius: 50%;
      margin-right: 5px;
      vertical-align: middle;
    }
  </style>
</head>

<body>
  <div class="header">
    <h2>Request #<?= esc($request['id']) ?> is Waiting for <b>Purchase Invoice</b></h2>
  </div>
  <div class="content">
    Hello <?= $userName ?>,

    <p>We noticed that your purchase invoicve for Request #<?= $request['id'] ?> has not been set to us yet. </p>
    <p>To ensure your order is processed without delay, please upload the purchase invoice as soon as possible.</p>


    You can upload by clicking the button below:<br>

    <a href="<?= base_url('customer/shipping/details/' . $request['id'] . '/#purchaseInvoice') ?>" class="btn">Details</a>

    <h3>Request Details</h3>
    <p><strong>Submitted:</strong> <?= date('F j, Y g:i A', strtotime($request['created_at'])) ?></p>

    If you have any questions or need assistance, feel free to contact our support team.

    Thank you for choosing our service!



    <p>Thank you,<br>Shippex Team</p>
  </div>
</body>

</html>