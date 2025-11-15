<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Download Your Label</title>
 <style>
  /* Base Styles */
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

  /* Header */
  .header {
   background: linear-gradient(135deg, #4E148C 0%, #6A1B9A 100%);
   color: #fff;
   padding: 30px 20px;
   text-align: center;
   position: relative;
  }

  .logo {
   font-size: 28px;
   font-weight: 700;
   margin-bottom: 10px;
   display: inline-flex;
   align-items: center;
   gap: 8px;
  }

  .logo-icon {
   width: 32px;
   height: 32px;
   background: rgba(255, 255, 255, 0.2);
   border-radius: 8px;
   display: flex;
   align-items: center;
   justify-content: center;
   background: transparent;
  }

  .header h1 {
   margin: 0;
   font-size: 24px;
   font-weight: 600;
  }

  /* Content */
  .content {
   padding: 40px 30px;
  }

  .greeting {
   font-size: 18px;
   margin-bottom: 25px;
   color: #4a4a4a;
  }

  .message {
   margin-bottom: 30px;
   color: #5a5a5a;
   line-height: 1.7;
  }

  /* Button */
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
   box-shadow: 0 4px 12px rgba(255, 102, 0, 0.25);
   transition: all 0.3s ease;
  }

  .btn:hover {
   transform: translateY(-2px);
   box-shadow: 0 6px 16px rgba(255, 102, 0, 0.35);
  }

  /* Link fallback */
  .link-fallback {
   margin-top: 20px;
   padding: 20px;
   background-color: #f8f9fa;
   border-radius: 8px;
   font-size: 14px;
   word-break: break-all;
  }

  .link-fallback a {
   color: #4E148C;
   text-decoration: none;
  }

  /* Footer */
  .footer {
   padding: 25px 30px;
   background-color: #f8f9fa;
   text-align: center;
   border-top: 1px solid #eaeaea;
   color: #777;
   font-size: 14px;
  }

  .footer p {
   margin: 8px 0;
  }

  .support-link {
   color: #4E148C;
   text-decoration: none;
  }

  /* Security Note */
  .security-note {
   background-color: #fff9f5;
   border-left: 4px solid #FF6600;
   padding: 15px;
   margin: 25px 0;
   border-radius: 0 8px 8px 0;
   font-size: 14px;
  }

  /* Responsive */
  @media (max-width: 480px) {
   .content {
    padding: 25px 20px;
   }

   .header {
    padding: 25px 15px;
   }

   .btn {
    padding: 12px 24px;
    font-size: 15px;
   }
  }
 </style>
</head>

<body>
 <div class="email-container">
  <!-- Header -->
  <div class="header">
   <div class="logo">
    <div class="logo-icon"><img src="<?= base_url('images/logo.png'); ?>" height="45px" width="45px"></div>
    Shippex
   </div>
   <h1>Your Label is Ready!</h1>
  </div>

  <!-- Content -->
  <div class="content">
   <p class="greeting">Hi <?= $name ?>,</p>

   <p class="message">
    Your label is ready, please download or see your label using below button and link:
   </p>

   <div class="btn-container">
    <a href="<?= $text ?>" class="btn">View Label</a>
   </div>



   <p class="message">
    If the button above doesn't work, copy and paste this link into your browser:
   </p>

   <div class="link-fallback">
    <a href="<?= $text ?>"><?= $text ?></a>
   </div>
  </div>

  <!-- Footer -->
  <div class="footer">
   <p>Thank you,<br><strong>The Shippex Team</strong></p>
   <p>Need help? <a href="#" class="support-link">Contact our support team</a></p>
   <p>&copy; 2023 Shippex. All rights reserved.</p>
  </div>
 </div>
</body>

</html>