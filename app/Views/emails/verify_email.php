<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <title>Email Confirmation - Shippex</title>
 <style>
  body {
   font-family: Arial, sans-serif;
   line-height: 1.6;
   color: #333;
   background-color: #f9f9f9;
   margin: 0;
   padding: 0;
  }

  .header {
   background-color: #4E148C;
   color: #fff;
   padding: 20px;
   text-align: center;
  }

  .header h2 {
   margin: 0;
   font-size: 24px;
  }

  .content {
   padding: 20px;
   background-color: #fff;
   margin: 20px auto;
   max-width: 600px;
   border-radius: 8px;
   box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
  }

  .content p {
   margin-bottom: 15px;
  }

  .btn {
   display: inline-block;
   padding: 10px 20px;
   background-color: #FF6600;
   color: #fff;
   text-decoration: none;
   border-radius: 5px;
   font-weight: bold;
   margin-top: 10px;
  }

  h3 {
   color: #4E148C;
  }

  .confirmation-code {
   font-size: 20px;
   font-weight: bold;
   color: #4E148C;
   background-color: #f2f2f2;
   padding: 10px 15px;
   border-radius: 5px;
   display: inline-block;
   margin: 10px 0;
  }
 </style>
</head>

<body>
 <div class="header">
  <h2>Email Confirmation</h2>
 </div>

 <div class="content">
  <p>Hello <?= esc($firstname) . ' ' . esc($lastname) ?>,</p>

  <p>Thank you for registering with Shippex. To complete your registration, please confirm your email address by clicking the confirmation link.</p>


  <p>If you prefer, you can also confirm your email directly by clicking the button below:</p>

  <a href="<?= esc($link) ?>" class="btn">Confirm Email</a>

  <p>If you did not request this email, please ignore it.</p>

  <p>Thank you,<br>Shippex Team</p>
 </div>
</body>

</html>