<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <title>Shippex Admin Dashboard</title>
 <meta name="viewport" content="width=device-width, initial-scale=1">

 <!-- Favicon -->
 <link rel="icon" href="<?= base_url('assets/img/favicon.ico') ?>" type="image/x-icon">

 <!-- Bootstrap 5 CDN -->
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
 <!-- Font Awesome -->
 <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
 <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
 <link rel="stylesheet" href="<?= base_url('assets/css/auth_style.css'); ?>">
 <?php if (!empty($defaultFont)): ?>
  <link href="https://fonts.googleapis.com/css2?family=<?= urlencode($defaultFont) ?>&display=swap" rel="stylesheet">

  <style>
   body {
    font-family: '<?= esc($defaultFont) ?>', sans-serif;
   }
  </style>
 <?php endif; ?>

</head>

<body>
 <?= view('partials/flash_message') ?>
 <div class="container-fluid">
  <div class="row">
   <!-- Sidebar -->
   <nav class="col-md-2 d-none d-md-block sidebar">
    <div class="sidebar-header">
     <div class="d-flex align-items-center justify-content-center">
      <a href="<?= base_url('/') ?>"><img src="<?= base_url('images/logo.png') ?>" alt="Shippex Logo" height="30" class="me-2"></a>
      <span class="fs-5 text-white">Admin Panel</span>
     </div>
    </div>
    <div class="position-sticky pt-1">
     <ul class="nav flex-column">
      <li class="nav-item">
       <a class="nav-link <?= (strpos(current_url(), '/dashboard') !== false) ? 'active' : '' ?>" href="<?= site_url(route_to('dashboard')) ?>">
        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
       </a>
      </li>
      <li class="nav-item">
       <a class="nav-link <?= (strpos(current_url(), '/shipping') !== false) ? 'active' : '' ?>" href="<?= base_url('shipping/requests') ?>">
        <i class="fas fa-users me-2"></i> Shipping Requests
       </a>
      </li>
      <li class="nav-item">
       <a class="nav-link" href="<?= base_url('admin/shopper/requests') ?>">
        <i class="fas fa-box me-2"></i> Shopper Requests
       </a>
      </li>
      <li class="nav-item">
       <a class="nav-link <?= (strpos(current_url(), '/warehouse') !== false) ? 'active' : '' ?>" href="<?= base_url('/warehouse') ?>">
        <i class="fas fa-location me-2"></i> Warehouses
       </a>
      </li>

      <li class="nav-item">
       <a class="nav-link <?= (strpos(current_url(), '/users') !== false) ? 'active' : '' ?>" href="#">
        <i class="fas fa-users me-2"></i> Users
       </a>
      </li>
      <li class="nav-item">
       <a class="nav-link <?= (strpos(current_url(), '/faqs') !== false) ? 'active' : '' ?>" href="<?= base_url('admin/faqs') ?>">
        <i class="fas fa-question me-2"></i> FAQs
       </a>
      </li>
      <li class="nav-item">
       <a class="nav-link <?= (strpos(current_url(), '/fonts') !== false) ? 'active' : '' ?>" href="<?= site_url('/admin/fonts/select') ?>">
        <i class="fas fa-font me-2"></i> Font Settings
       </a>
      </li>
      <!-- Divider -->
      <div class="d-flex align-items-center my-3 text-secondary">
       <div class="flex-grow-1">
        <hr class="m-0 text-secondary">
       </div>
       <span class="px-2 small text-uppercase">CMS</span>
       <div class="flex-grow-1">
        <hr class="m-0 text-secondary">
       </div>
      </div>
      <li class="nav-item">
       <a class="nav-link <?= (strpos(current_url(), '/admin/cms') !== false) ? 'active' : '' ?> " data-bs-toggle="collapse" href="#homeSection" role="button" aria-expanded="false" aria-controls="homeSection">
        <i class="fas fa-home me-2"></i> Home Page
        <i class="fas fa-chevron-down float-end mt-1 small"></i>
       </a>
       <div class="collapse ps-1" id="homeSection">
        <ul class="nav flex-column">
         <li class="nav-item">
          <a class="nav-link" href="<?= site_url('/admin/cms/hero-section/edit') ?>">
           <i class="fas fa-font me-2"></i>Hero Section
          </a>
         </li>
         <li class="nav-item">
          <a class="nav-link" href="<?= site_url('/admin/cms/how-it-works') ?>">
           <i class="fas fa-wrench me-2"></i> How it works
          </a>
         </li>
         <li class="nav-item">
          <a class="nav-link" href="<?= site_url('/admin/cms/locations') ?>">
           <i class="fas fa-map me-2"></i> Locations
          </a>
         </li>
         <li class="nav-item">
          <a class="nav-link" href="<?= site_url('/admin/cms/delivered-today') ?>">
           <i class="fas fa-box me-2"></i> Delivered
          </a>
         </li>
         <li class="nav-item">
          <a class="nav-link" href="<?= site_url('/admin/cms/promo-cards') ?>">
           <i class="fas fa-star me-2"></i> Promotion Card
          </a>
         </li>
        </ul>
       </div>
      </li>
      <li class="nav-item">
       <a class="nav-link <?= (strpos(current_url(), '/admin/blog') !== false) ? 'active' : '' ?>" href="<?= base_url('/admin/blog/posts') ?>">
        <i class="fas fa-list me-2"></i> Blogs
       </a>
      </li>
      <li class="nav-item">
       <a class="nav-link <?= (strpos(current_url(), '/admin/w_pages') !== false) ? 'active' : '' ?>" href="<?= base_url('/admin/w_pages') ?>">
        <i class="fas fa-warehouse me-2"></i> Warehouses Page
       </a>
      </li>


      <li class="nav-item">
       <a class="nav-link" href="<?= base_url('admin/how-it-works') ?>">
        <i class="fas fa-question me-2"></i> How it Works
       </a>
      </li>
     </ul>
    </div>
   </nav>

   <!-- Main Content -->

   <main class="col-md-10 ms-sm-auto m-0 p-0">
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top mb-4">
     <div class="container-fluid">
      <div class="d-flex align-items-center">
       <button class="btn btn-sm d-md-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarCollapse">
        <i class="fas fa-bars"></i>
       </button>
       <span class="navbar-brand mb-0 h6">
        <i class="fas fa-user-circle me-2 text-shippex-purple"></i>
        Welcome, <?= esc(session()->get('role')) ?>
       </span>
      </div>
      <div class="ms-auto">
       <a class="btn btn-sm btn-shippex-orange" href="<?= base_url('logout'); ?>">
        <i class="fas fa-sign-out-alt me-1"></i> Logout
       </a>
      </div>
     </div>
    </nav>
    <div class="row content">

     <?= $this->renderSection('content') ?>
    </div>
   </main>
  </div>
 </div>

 <!-- Mobile Sidebar Offcanvas -->
 <div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="sidebarCollapse">
  <div class="offcanvas-header">
   <h5 class="offcanvas-title">Shippex Admin</h5>
   <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body p-0">
   <div class="sidebar">
    <!-- Same sidebar content as desktop -->
   </div>
  </div>
 </div>

 <!-- jquery -->
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <!-- Bootstrap JS -->
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 <script>
  // Mobile sidebar toggle
  document.addEventListener('DOMContentLoaded', function() {
   var sidebarCollapse = document.getElementById('sidebarCollapse');
   var sidebar = document.querySelector('.sidebar');

   // Clone sidebar content for mobile
   var sidebarContent = sidebar.innerHTML;
   document.querySelector('.offcanvas-body .sidebar').innerHTML = sidebarContent;

   // Activate current nav item
   var currentPath = window.location.pathname;
   document.querySelectorAll('.sidebar .nav-link').forEach(function(link) {
    if (link.getAttribute('href') === currentPath) {
     link.classList.add('active');
    }
   });
  });
 </script>
 <?= $this->renderSection('script') ?>
</body>

</html>