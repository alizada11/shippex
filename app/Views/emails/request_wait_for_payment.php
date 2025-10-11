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
  <p>Your request has been accepted by the admin. The new price is <strong>$<?= esc($request['price']) ?></strong> and the status is <strong>Wait for Payment</strong>.</p>

  <h3>Request Details</h3>
  <p><strong>Submitted:</strong> <?= date('F j, Y g:i A', strtotime($request['created_at'])) ?></p>
  <p><strong>Delivery Option:</strong> <?= esc($request['delivery_description']) ?: 'N/A' ?></p>
  <p><strong>Delivery Notes:</strong> <?= esc($request['delivery_notes']) ?: 'N/A' ?></p>
  <p><strong>Allow Alternate Retailers:</strong> <?= $request['use_another_retailer'] ? 'Yes' : 'No' ?></p>

  <h3>Items</h3>
  <?php if (!empty($items)): ?>
   <table>
    <thead>
     <tr>
      <th>#</th>
      <th>Name</th>
      <th>URL</th>
      <th>Size</th>
      <th>Color</th>
      <th>Qty</th>
      <th>Instructions</th>
     </tr>
    </thead>
    <tbody>
     <?php foreach ($items as $index => $item): ?>
      <tr>
       <td><?= $index + 1 ?></td>
       <td><?= esc($item['name']) ?></td>
       <td><a href="<?= esc($item['url']) ?>"><?= esc($item['url']) ?></a></td>
       <td><?= esc($item['size']) ?: '-' ?></td>
       <td>
        <?php if (!empty($item['color'])): ?>
         <span class="color-dot" style="background-color: <?= esc($item['color']) ?>;"></span>
         <?= esc($item['color']) ?>
        <?php else: ?>
         -
        <?php endif; ?>
       </td>
       <td><?= esc($item['quantity']) ?></td>
       <td><?= esc($item['instructions']) ?: '-' ?></td>
      </tr>
     <?php endforeach; ?>
    </tbody>
   </table>
  <?php else: ?>
   <p>No items found for this request.</p>
  <?php endif; ?>

  <p>Please <a href="<?= base_url('shopper/requests/view/' . $request['id']) ?>" class="btn">View Request</a> to complete your payment.</p>

  <p>Thank you,<br>Shippex Team</p>
 </div>
</body>

</html>