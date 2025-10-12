<!DOCTYPE html>
<html>

<head>
 <meta charset="UTF-8">
 <title>Reset Password Link</title>
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

 </div>
 <div class="content">
  <p>Hi </p>
  <p>Your password reset link is:<a href="<?= $text ?>"></a></p>

  <p>Thank you,<br>Shippex Team</p>
 </div>
</body>

</html>