<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <title>Shipping Price Set - Shippex</title>
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
  <h2>Shipping Price Announced</h2>
 </div>

 <div class="content">
  <p>Hello <?= esc($userName)  ?>,</p>

  <p>Thank you for choosing Shippex. To complete your request, please select a shipping option by clicking the below link.</p>



  <a href="<?= esc($reqLink) ?>" class="btn">See request details!</a>

  <p>Please select one option to proceed your request.</p>

  <p>Thank you,<br>Shippex Team</p>
 </div>
</body>

</html>