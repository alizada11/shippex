<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <title>Dashboard</title>
 <meta name="viewport" content="width=device-width, initial-scale=1">

 <!-- Bootstrap 5 CDN -->
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
 <!-- Font Awesome -->
 <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
 <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

 <?php if (!empty($defaultFont)): ?>
  <link href="https://fonts.googleapis.com/css2?family=<?= urlencode($defaultFont) ?>&display=swap" rel="stylesheet">

  <style>
   body {
    font-family: '<?= esc($defaultFont) ?>', sans-serif;
    font-size: 1rem;
   }
  </style>
 <?php endif; ?>
 <link rel="stylesheet" href="<?= base_url('assets/css/auth_style.css') ?>">
</head>

<body>

 <?= view('partials/flash_message') ?>
 <div class="container-fluid">
  <div class="row">
   <!-- Sidebar -->
   <nav class="col-md-2 d-none d-md-block sidebar">
    <div class="sidebar-header">
     <div class="d-flex align-items-center justify-content-center">
      <a href="<?= base_url('/') ?>"><img src="<?= base_url('images/logo.png') ?>" alt="Shippex Logo" height="50" class="me-2"></a>
      <span class="fs-5 text-white"> Dashboard</span>
     </div>
    </div>
    <div class="position-sticky pt-3">

     <ul class="nav flex-column">
      <li class="nav-item">
       <a class="nav-link <?= (strpos(current_url(), '/dashboard') !== false) ? 'active' : '' ?>" href="<?= site_url(route_to('dashboard')) ?>"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
      </li>
      <li class="nav-item">
       <a class="nav-link <?= (strpos(current_url(), '/customer/shipping') !== false) ? 'active' : '' ?>" href="<?= base_url('customer/shipping/requests') ?>"><i class="fas fa-users me-2"></i> Shipment History</a>
      </li>
      <li class="nav-item">
       <a class="nav-link <?= (strpos(current_url(), '/shopper') !== false) ? 'active' : '' ?>" data-bs-toggle="collapse" href="#personalShopper" role="button" aria-expanded="false" aria-controls="personalShopper">
        <i class="fas fa-shopping-bag me-2"></i> Personal Shopper
       </a>
       <div class="collapse ps-4" id="personalShopper">
        <ul class="nav flex-column">
         <li class="nav-item">
          <a class="nav-link" href="<?= site_url('/shopper') ?>"> <i class="fas fa-cart-plus"></i> Request </a>
         </li>
         <li class="nav-item">
          <a class="nav-link" href="<?= site_url('/shopper/requests') ?>"><i class="fas fa-file-alt"></i> My Requests</a>
         </li>
         <!-- Add more submenu items here -->
        </ul>
       </div>
      </li>
      <li class="nav-item">
       <a class="nav-link <?= (strpos(current_url(), '/profile') !== false) ? 'active' : '' ?> <?= (strpos(current_url(), '/addresses') !== false) ? 'active' : '' ?>" data-bs-toggle="collapse" href="#proffile" role="button" aria-expanded="false" aria-controls="proffile">
        <i class="fas fa-user me-2"></i> Profile
       </a>
       <div class="collapse ps-4" id="proffile">
        <ul class="nav flex-column">
         <li class="nav-item">
          <a class="nav-link" href="<?= base_url('/profile') ?>"> My Profile</a>
         </li>
         <li class="nav-item">
          <a class="nav-link" href="<?= site_url('/addresses') ?>"> My Addresses</a>
         </li>
         <li class="nav-item">
          <a class="nav-link" href="<?= site_url('/profile/warehouse-addresses') ?>"> Warehouse Addresses</a>
         </li>
         <!-- Add more submenu items here -->
        </ul>
       </div>
      </li>

      <li class="nav-item">
       <a class="nav-link" href="<?= base_url('customers/faqs') ?>"><i class="fas fa-chart-line me-2"></i> FAQ</a>
      </li>

     </ul>
    </div>
   </nav>

   <!-- Main Content -->
   <main class="col-md-9 ms-sm-auto col-lg-10 content">
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
     <div class="container-fluid">
      <a class="navbar-brand" href="#">Welcome, <?= session()->get('role') ?></a>
      <div class="ms-auto">
       <a class="btn btn-outline-danger btn-sm" href="<?= base_url('logout'); ?>"><i class="fas fa-sign-out-alt"></i> Logout</a>
      </div>
     </div>
    </nav>
    <?= $this->renderSection('content') ?>
   </main>
  </div>
 </div>
 <!-- jquery -->

 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <!-- Bootstrap JS -->
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 <?= $this->renderSection('script') ?>
</body>

</html>