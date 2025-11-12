<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <title><?= isset($title) ? esc($title) : ' ' ?></title>
 <meta name="viewport" content="width=device-width, initial-scale=1">

 <!-- Favicon -->
 <link rel="icon" href="<?= base_url('assets/img/favicon.ico') ?>" type="image/x-icon">

 <!-- Bootstrap 5 CDN -->
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
 <!-- Font Awesome -->
 <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
 <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
 <link href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@6.6.6/css/flag-icons.min.css" rel="stylesheet">
 <link rel="stylesheet" href="<?= base_url('assets/css/auth_style.css'); ?>">
 <?php if (!empty($defaultFont)): ?>
  <link href="https://fonts.googleapis.com/css2?family=<?= urlencode($defaultFont) ?>&display=swap" rel="stylesheet">
  <?= $this->renderSection('styles') ?>

  <style>
   body {
    font-family: '<?= esc($defaultFont) ?>', sans-serif;
   }
  </style>
 <?php endif; ?>
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
      <?php
      // Get current URL
      $currentUrl = current_url();

      // Check if the URL contains 'packages/' followed by a number
      $isPackageActive = preg_match('#/packages(/create/\d+|/\d+(/edit)?)?#', $currentUrl);
      ?>

      <li class="nav-item">
       <a class="nav-link <?= $isPackageActive ? 'active' : '' ?>"
        data-bs-toggle="collapse"
        href="#packageInbox"
        role="button"
        aria-expanded="<?= $isPackageActive ? 'true' : 'false' ?>"
        aria-controls="packageInbox">
        <i class="fas fa-box me-2"></i>Package Inbox
        <i class="fas fa-chevron-down float-end mt-1 small"></i>
       </a>
       <div class="collapse ps-1 <?= $isPackageActive ? 'show' : '' ?>" id="packageInbox">
        <ul class="nav flex-column">
         <?= adminWarehousesMenu(); ?>
        </ul>
       </div>
      </li>

      <li class="nav-item">
       <a class="nav-link <?= (strpos(current_url(), 'admin/combine-requests') !== false) ? 'active' : '' ?>" href="<?= base_url('admin/combine-requests') ?>">
        <i class="fas fa-boxes me-2"></i> Combine & Repack
       </a>
      </li>
      <li class="nav-item">
       <a class="nav-link <?= (strpos(current_url(), 'admin/dispose-return') !== false) ? 'active' : '' ?>" href="<?= base_url('admin/dispose-return') ?>">
        <i class="fas fa-trash me-2"></i> Disposal/Return Requests
       </a>
      </li>

      <li class="nav-item">
       <a class="nav-link <?= (strpos(current_url(), '/shipping') !== false) ? 'active' : '' ?>" href="<?= base_url('shipping/requests') ?>">
        <i class="fas fa-users me-2"></i> Shipping Requests
       </a>
      </li>
      <li class="nav-item">
       <a class="nav-link <?= (strpos(current_url(), '/admin/shopper') !== false) ? 'active' : '' ?>" href="<?= base_url('admin/shopper/requests') ?>" href="<?= base_url('admin/shopper/requests') ?>">
        <i class="fas fa-box me-2"></i> Shopper Requests
       </a>
      </li>
      <li class="nav-item">
       <a class="nav-link <?= (strpos(current_url(), '/warehouse') !== false) ? 'active' : '' ?>" href="<?= base_url('/warehouse') ?>">
        <i class="fas fa-location me-2"></i> Warehouses
       </a>
      </li>

      <li class="nav-item">
       <a class="nav-link <?= (strpos(current_url(), '/users') !== false) ? 'active' : '' ?>" href="<?= base_url('/users') ?>">
        <i class="fas fa-users me-2"></i> Users
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
       <a class="nav-link <?= (strpos(current_url(), '/faqs') !== false) ? 'active' : '' ?>" href="<?= base_url('admin/faqs') ?>">
        <i class="fas fa-question-circle me-2"></i> FAQs
       </a>
      </li>
      <li class="nav-item">
       <a class="nav-link" href="<?= base_url('admin/how-it-works') ?>">
        <i class="fas fa-question me-2"></i> How it Works
       </a>
      </li>
      <li class="nav-item">
       <a class="nav-link btn btn-sm btn-shippex-orange d-block d-md-none" href="<?= base_url('logout'); ?>">
        <i class="fas fa-sign-out-alt me-1"></i> Logout
       </a>
      </li>
     </ul>
    </div>
   </nav>

   <!-- Main Content -->

   <main class="col-md-10 ms-sm-auto m-0 p-0">
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top mb-4 noprint">
     <div class="container-fluid">
      <div class="d-flex align-items-center">
       <button class="btn btn-sm d-md-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarCollapse">
        <i class="fas fa-bars"></i>
       </button>
       <span class="d-none d-md-inline navbar-brand mb-0 h6">
        <i class="fas fa-user-circle me-2 text-shippex-purple"></i>
        Welcome, <?= esc(session()->get('full_name'))  ?>
       </span>
       <span class="d-block d-md-none navbar-brand mb-0 h6">
        <i class="fas fa-user-circle me-2 text-shippex-purple"></i>
        Welcome
       </span>
      </div>
      <div class="ms-lg-auto ms-md-auto">
       <a class="btn btn-sm btn-shippex" href="<?= base_url('/'); ?>">
        <i class="fas fa-eye me-1"></i> Back to Site
       </a>
       <a class="btn btn-sm btn-shippex-orange d-none d-md-inline" href="<?= base_url('logout'); ?>">
        <i class="fas fa-sign-out-alt me-1"></i> Logout
       </a>
      </div>
     </div>
    </nav>
    <div class="content">

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
 <script src="<?= base_url('js/download-helper.js'); ?>"></script>
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

 <script>
  // Initialize download functionality
  document.addEventListener('DOMContentLoaded', function() {
   // Page download buttons
   document.querySelectorAll('.download-page-btn').forEach(btn => {

    btn.addEventListener('click', function() {
     const filename = this.getAttribute('data-filename');
     const title = this.getAttribute('data-title');

     DownloadHelper.downloadPage({
      filename: filename,
      title: title,
      format: 'pdf' // or 'pdf' when you implement PDF generation
     });
    });
   });

   // Global download trigger (you can use this anywhere)
   window.downloadCurrentPage = function(options = {}) {
    return DownloadHelper.downloadPage(options);
   };
  });
 </script>
 <?= $this->renderSection('script') ?>
</body>

</html>