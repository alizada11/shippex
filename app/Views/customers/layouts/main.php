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
  <link href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@6.6.6/css/flag-icons.min.css" rel="stylesheet">
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
            <a href="<?= base_url('/') ?>"><img src="<?= base_url('images/logo.png') ?>" alt="Shippex Logo" height="50" class="me-2"></a>
            <span class="fs-5 text-white"> Dashboard</span>
          </div>
        </div>
        <div class="position-sticky pt-3">

          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link <?= (strpos(current_url(), '/dashboard') !== false) ? 'active' : '' ?>" href="<?= site_url(route_to('dashboard')) ?>"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
            </li>
            <?php
            // Get current URL
            $currentUrl = current_url();

            // Check if the URL contains 'packages/' followed by a number
            $isPackageActive = preg_match('#/packages/\d+#', $currentUrl);
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
              <a class="nav-link <?= (strpos(current_url(), '/customer/shipping') !== false) ? 'active' : '' ?>" href="<?= base_url('customer/shipping/requests') ?>"><i class="fas fa-truck-fast me-2"></i> Shipment History</a>
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
              <a class="nav-link <?= (strpos(current_url(), '/warehouse-requests') !== false) ? 'active' : '' ?>" href="<?= site_url('/warehouse-requests/my-requests') ?>"><i class="fas fa-warehouse me-2"></i> My Warehouses</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= (strpos(current_url(), '/warehouse-addresses') !== false) ? 'active' : '' ?>" href="<?= site_url('/warehouse-addresses') ?>"><i class="fas fa-warehouse me-2"></i> Warehouses</a>
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


                  <!-- Add more submenu items here -->
                </ul>
              </div>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="<?= base_url('customers/faqs') ?>"><i class="fas fa-question-circle me-2"></i> FAQ</a>
            </li>

          </ul>
        </div>
      </nav>

      <!-- Main Content -->
      <main class="col-md-9 ms-sm-auto col-lg-10 content">
        <nav class="noprint navbar navbar-expand-lg navbar-light bg-light mb-4">
          <div class="container-fluid">
            <a class="navbar-brand" style="text-transform: capitalize;" href="#">Welcome, <?= session()->get('full_name') ?></a>
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

  <script src="<?= base_url('js/download-helper.js'); ?>"></script>

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
            format: 'html' // or 'pdf' when you implement PDF generation
          });
        });
      });

      // Global download trigger (you can use this anywhere)
      window.downloadCurrentPage = function(options = {}) {
        return DownloadHelper.downloadPage(options);
      };
    });
  </script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const buttons = document.querySelectorAll('.warehouse-select-btn');

      buttons.forEach(btn => {
        btn.addEventListener('click', function(e) {
          e.preventDefault();

          const warehouseId = this.dataset.warehouseId;
          const warehouseName = this.dataset.warehouseName;

          if (!warehouseId) return;

          // Optional: confirm
          if (!confirm(`Are you sure you want to request this warehouse?`)) return;

          fetch("<?= site_url('warehouse-requests/request') ?>", {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
              },
              body: 'warehouse_id=' + encodeURIComponent(warehouseId)
            })
            .then(res => res.json())
            .then(data => {
              if (data.status === 'success') {
                alert(data.message);
                // Optionally disable the button
                btn.textContent = 'You Own it';
              } else {
                alert(data.message);
              }
            })
            .catch(err => {
              console.error(err);
              alert('Something went wrong.');
            });
        });
      });
    });
  </script>
  <?= $this->renderSection('script') ?>

</body>

</html>