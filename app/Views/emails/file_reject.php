<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>File Rejected</title>
 <style>
  body {
   font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
   line-height: 1.6;
   color: #333;
   margin: 0;
   padding: 0;
   background-color: #f8f9fa;
  }

  .email-container {
   max-width: 600px;
   margin: 0 auto;
   background-color: #ffffff;
   border-radius: 12px;
   overflow: hidden;
   box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  }

  .header {
   background: linear-gradient(135deg, #B71C1C 0%, #D32F2F 100%);
   color: #fff;
   padding: 30px 20px;
   text-align: center;
  }

  .logo {
   font-size: 28px;
   font-weight: 700;
   margin-bottom: 10px;
   display: inline-flex;
   align-items: center;
   gap: 8px;
  }

  .content {
   padding: 40px 30px;
  }

  .greeting {
   font-size: 18px;
   margin-bottom: 25px;
   color: #4a4a4a;
  }

  .message {
   margin-bottom: 25px;
   color: #5a5a5a;
   line-height: 1.7;
  }

  .btn-container {
   text-align: center;
   margin: 35px 0;
  }

  .btn {
   display: inline-block;
   padding: 14px 32px;
   background: linear-gradient(135deg, #FF6600 0%, #FF8C42 100%);
   color: #fff;
   text-decoration: none;
   border-radius: 8px;
   font-weight: 600;
   font-size: 16px;
  }

  .footer {
   padding: 25px 30px;
   background-color: #f8f9fa;
   text-align: center;
   border-top: 1px solid #eaeaea;
   color: #777;
   font-size: 14px;
  }
 </style>
</head>

<body>
 <div class="email-container">

  <!-- Header -->
  <div class="header">
   <div class="logo">
    <img src="<?= base_url('images/logo.png'); ?>" height="45" width="45">
    Shippex
   </div>
   <h1>File Rejected</h1>
  </div>

  <!-- Content -->
  <div class="content">
   <p class="greeting">Hi <?= esc($name) ?>,</p>

   <p class="message">
    Unfortunately, the <strong><?= esc($file_type) ?></strong> you uploaded has been reviewed and could not be approved.
   </p>

   <p class="message">
    To proceed with your request, please upload a new and valid <strong><?= esc($file_type) ?></strong> that meets our requirements.
   </p>

   <div class="btn-container">
    <a href="<?= esc($text) ?>" class="btn">Upload New File</a>
   </div>

   <p class="message">
    If you believe this was a mistake or need help, feel free to contact our support team.
   </p>
  </div>

  <!-- Footer -->
  <div class="footer">
   <p>Thank you,<br><strong>The Shippex Team</strong></p>
   <p>Need help? <a href="#" style="color:#4E148C;">Contact Support</a></p>
   <p>&copy; 2023 Shippex. All rights reserved.</p>
  </div>

 </div>
</body>

</html>