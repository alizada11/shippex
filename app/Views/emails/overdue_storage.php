<!DOCTYPE html>
<html>

<head>
 <meta charset="UTF-8">
 <title>$<?= $overdue_fee; ?>Overdue Sotrage fee.</title>
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
  <h2>You have a unpaid overdue fee.</h2>
 </div>
 <div class="content">
  Hello Dear <?= $userName ?>,

  <p>We noticed that your package has an overdue fee of <b>$<?= $overdue_fee; ?></b>. </p>
  <p>To ensure your order is processed without delay, please check your package and pay the amount.</p>


  <a href="<?= $link ?>" class="btn">View Package</a>
  <br>
  If you have any questions or need assistance, feel free to contact our support team.

  Thank you for choosing our service!



  <p>Thank you,<br>Shippex Team</p>
 </div>
</body>

</html>