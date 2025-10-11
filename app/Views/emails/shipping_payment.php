<!DOCTYPE html>
<html>

<head>
 <meta charset="UTF-8">
 <title>Request #<?= esc($request['id']) ?> - Wait for Payment</title>
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
   padding: 10px 15px;
   background-color: #FF6600;
   color: #fff;
   text-decoration: none;
   border-radius: 4px;
  }

  table {
   width: 100%;
   border-collapse: collapse;
   margin-top: 15px;
  }

  table,
  th,
  td {
   border: 1px solid #ddd;
  }

  th,
  td {
   padding: 8px;
   text-align: left;
  }

  th {
   background-color: #4E148C;
   color: #fff;
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
  <h2>Request #<?= esc($request['id']) ?> is Waiting for Payment</h2>
 </div>
 <div class="content">
  <p>Hi <?= esc($userName) ?>,</p>
  <p>Your request has been accepted by the admin. <strong>Wait for Payment</strong>.</p>

  <h3>Request Details</h3>
  <p><strong>Submitted:</strong> <?= date('F j, Y g:i A', strtotime($request['created_at'])) ?></p>
  <p><strong>Total Charge:</strong> <?= esc($request['total_charge']) ?: 'N/A' ?></p>


  <p>Please <a href="<?= base_url('customer/shipping/details/' . $request['id'] . '/#purchaseInvoice') ?>" class="btn">View Request</a> to complete your payment.</p>

  <p>Thank you,<br>Shippex Team</p>
 </div>
</body>

</html>