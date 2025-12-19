<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <title><?= esc($title ?? 'Admin Notification') ?></title>
 <style>
  body {
   font-family: Arial, sans-serif;
   background-color: #f5f5f5;
   margin: 0;
   padding: 0;
  }

  .container {
   max-width: 600px;
   margin: 20px auto;
   background: #fff;
   padding: 30px;
   border-radius: 10px;
   box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  }

  h2 {
   color: #333;
  }

  p {
   color: #555;
   line-height: 1.6;
  }

  .btn {
   display: inline-block;
   padding: 12px 25px;
   background: #FF6600;
   color: #fff;
   text-decoration: none;
   border-radius: 6px;
   margin-top: 20px;
  }
 </style>
</head>

<body>
 <div class="container">
  <h2>Admin Notification</h2>

  <p><strong>Action:</strong> <?= esc($actionDescription) ?></p>

  <?php if (!empty($modelName)): ?>
   <p><strong>Model/Table:</strong> <?= esc($modelName) ?></p>
  <?php endif; ?>

  <?php if (!empty($recordId)): ?>
   <p><strong>Record ID:</strong> <?= esc($recordId) ?></p>
  <?php endif; ?>

  <?php if (!empty($userName) || !empty($userEmail)): ?>
   <p><strong>User:</strong> <?= esc($userName ?? 'Unknown') ?> (<?= esc($userEmail ?? 'N/A') ?>)</p>
  <?php endif; ?>

  <?php if (!empty($actionLink)): ?>
   <p>Click the button below to view details:</p>
   <a href="<?= esc($actionLink) ?>" class="btn">View Details</a>
   <p>Or copy this link: <a href="<?= esc($actionLink) ?>"><?= esc($actionLink) ?></a></p>
  <?php endif; ?>

  <p>--<br>Shippex System</p>
 </div>
</body>

</html>