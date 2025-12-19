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
  <!-- show user info -->

  <div class="modal fade user-info-modal" id="userInfoModal" tabindex="-1" aria-labelledby="userInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="userInfoModalLabel">User Information</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div id="user-info-content">
            <div class="loading-animation">
              <div class="loading-spinner"></div>
              <div class="loading-text">Loading...</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="row dashboard-content">
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
                <i class="fas fa-trash me-2"></i> Disposal/Return
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
            <li class="nav-item">
              <a class="nav-link <?= (strpos(current_url(), '/profile') !== false) ? 'active' : '' ?>" href="<?= site_url('/profile') ?>">
                <i class="fas fa-user me-2"></i> Profile
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

      <main class="col-md-10 ms-sm-auto m-0 p-0 main-content">
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

            <div class="d-flex gap-2 align-items-center">

              <div class="notification-wrapper dropdown me-3">
                <a href="#" class="nav-link dropdown-toggle position-relative" id="notificationDropdown"
                  role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fas fa-bell fa-lg"></i>
                  <!-- Badge for unread notifications -->
                  <span class="notification-badge" id="notificationCount">0</span>
                </a>

                <div class="dropdown-menu dropdown-menu-end notification-dropdown"
                  aria-labelledby="notificationDropdown">
                  <div class="dropdown-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold">Notifications</h6>
                    <button class="btn btn-sm btn-link p-0 text-primary" id="markAllRead">Mark all as read</button>
                  </div>
                  <div class="dropdown-divider m-0"></div>

                  <!-- Notification Items Container -->
                  <div id="notificationsContainer" style="min-height: 100px;">
                    <div class="text-center p-4">
                      <div class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                      </div>
                      <span class="ms-2 text-muted">Loading notifications...</span>
                    </div>
                  </div>

                  <div class="view-all-link">
                    <a href="<?= base_url('notifications/all') ?>" class="text-decoration-none">View all notifications</a>
                  </div>
                </div>
              </div>
              <a class="btn btn-sm btn-shippex" href="<?= base_url('/'); ?>">
                <i class="fas fa-globe me-1"></i> Website
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
    $(document).on('click', '.user-info-link', function() {
      const userId = $(this).data('user-id');
      $('#userInfoModal').modal('show');
      $('#user-info-content').html(`
    <div class="loading-animation">
      <div class="loading-spinner"></div>
      <div class="loading-text">Loading...</div>
    </div>
  `);

      $.ajax({
        url: "<?= site_url('user/info') ?>/" + userId,
        type: "GET",
        dataType: "json",
        success: function(res) {
          if (res.error) {
            $('#user-info-content').html('<div class="error-message">' + res.error + '</div>');
          } else {
            $('#userInfoModalLabel').html(`
              <i class="fas fa-user"></i> ${res.fullname}`);
            $('#user-info-content').html(`
          <div class="user-details">
            
            <div class="info-list">
              <div class="info-item">
                <span class="info-label">Joined:</span>
                <span class="info-value">${res.joined_date}</span>
              </div>
              <div class="info-item">
                <span class="info-label">Email:</span>
                <span class="info-value">${res.email}</span>
              </div>
              <div class="info-item">
                <span class="info-label">User Name:</span>
                <span class="info-value">${res.username}</span>
              </div>
              <div class="info-item">
                <span class="info-label">ID:</span>
                <span class="info-value">${res.id}</span>
              </div>
            </div>
            <div class="stats-grid">
              <div class="stat-card">
                <div class="stat-value">${res.total_bookings}</div>
                <div class="stat-label">Bookings</div>
              </div>
              <div class="stat-card">
                <div class="stat-value">${res.total_shopper_requests}</div>
                <div class="stat-label">Shopper Requests</div>
              </div>
              <div class="stat-card">
                <div class="stat-value">${res.total_packages}</div>
                <div class="stat-label">Packages</div>
              </div>
              <div class="stat-card">
                <div class="stat-value">${parseInt(res.total_bookings) + parseInt(res.total_shopper_requests) + parseInt(res.total_packages)}</div>
                <div class="stat-label">Total Activities</div>
              </div>
            </div>
          </div>
        `);
          }
        },
        error: function() {
          $('#user-info-content').html('<div class="error-message">Failed to load user info.</div>');
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
  <script>
    // Global variable to store notifications
    let currentNotifications = [];
    let previousUnreadCount = 0;
    const BASE_URL = '<?= base_url() ?>';


    // Function to get icon based on action
    function getNotificationIcon(action) {
      const icons = {
        'commented': 'fas fa-comment',
        'approved': 'fas fa-check-circle',
        'assigned': 'fas fa-user-check',
        'created': 'fas fa-plus-circle',
        'updated': 'fas fa-edit',
        'uploaded': 'fas fa-upload',
        'deleted': 'fas fa-trash',
        'default': 'fas fa-bell',
        'payment': 'fas fa-dollar-sign'
      };

      return icons[action] || icons['default'];
    }

    // Function to format time (friendly format)
    function formatTime(dateString) {

      // Force UTC parsing
      const notificationDate = new Date(dateString.replace(' ', 'T') + 'Z');
      const now = new Date();

      const diffMs = now - notificationDate;

      const diffSecs = Math.floor(diffMs / 1000);
      const diffMins = Math.floor(diffSecs / 60);
      const diffHours = Math.floor(diffMins / 60);
      const diffDays = Math.floor(diffHours / 24);

      if (diffSecs < 60) return 'Just now';
      if (diffMins < 60) return `${diffMins} min ago`;
      if (diffHours < 24) return `${diffHours} hour${diffHours !== 1 ? 's' : ''} ago`;

      if (diffDays === 1) {
        return `Yesterday at ${notificationDate.toLocaleTimeString([], {
      hour: '2-digit',
      minute: '2-digit'
    })}`;
      }

      if (diffDays < 7) return `${diffDays} days ago`;

      return notificationDate.toLocaleString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
      });
    }




    // Function to update notification badge
    function updateNotificationBadge(count) {
      const badgeElement = document.getElementById('notificationCount');
      if (badgeElement) {
        // Update count
        badgeElement.textContent = count > 99 ? '99+' : count;

        // Show/hide badge
        if (count > 0) {
          badgeElement.style.display = 'flex';

          // Add pulse animation for new notifications
          if (count > previousUnreadCount) {
            badgeElement.classList.add('pulse');
            setTimeout(() => {
              badgeElement.classList.remove('pulse');
            }, 3000);
          }
        } else {
          badgeElement.style.display = 'none';
        }

        previousUnreadCount = count;
      }

      // Update page title if there are unread notifications
      updatePageTitle(count);
    }

    // Function to update page title with notification count
    function updatePageTitle(unreadCount) {
      const baseTitle = document.title.replace(/^\(\d+\)\s*/, '');
      if (unreadCount > 0) {
        document.title = `(${unreadCount}) ${baseTitle}`;
      } else {
        document.title = baseTitle;
      }
    }

    // Function to mark notification as read
    async function markAsRead(notificationId) {
      try {
        const response = await fetch(`${BASE_URL}/notifications/read/${notificationId}`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        });

        const result = await response.json();

        if (result.success) {
          // Update the notification in the current list
          const notificationIndex = currentNotifications.findIndex(n => n.id == notificationId);
          if (notificationIndex !== -1) {
            currentNotifications[notificationIndex].is_read = true;
            renderNotifications(currentNotifications);
          }
          showToast('Notification marked as read');
        }
      } catch (error) {
        console.error('Error marking notification as read:', error);
        showToast('Error marking notification as read', 'error');
      }
    }

    // Function to mark all notifications as read
    async function markAllAsRead() {
      try {
        const response = await fetch(`${BASE_URL}/notifications/read-all`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        });

        const result = await response.json();

        if (result.success) {
          // Update all notifications in the current list
          currentNotifications.forEach(notification => {
            notification.is_read = true;
          });
          renderNotifications(currentNotifications);
          showToast('All notifications marked as read');
        }
      } catch (error) {
        console.error('Error marking all as read:', error);
        showToast('Error marking all as read', 'error');
      }
    }

    // Function to render notifications
    function renderNotifications(notifications) {
      const container = document.getElementById('notificationsContainer');
      const unreadCount = notifications.filter(n => !n.is_read).length;

      // Update badge count
      updateNotificationBadge(unreadCount);

      // Clear container
      container.innerHTML = '';

      // Check if there are notifications
      if (notifications.length === 0) {
        container.innerHTML = `
                <div class="no-notifications">
                    <i class="far fa-bell fa-3x mb-3 text-muted"></i>
                    <p class="mb-0">No notifications yet</p>
                    <small class="text-muted">You're all caught up!</small>
                </div>
            `;
        return;
      }

      // Add notification items
      notifications.forEach(notification => {
        const notificationElement = document.createElement('div');
        notificationElement.className = `notification-item ${notification.is_read ? '' : 'unread'}`;

        // Ensure link is properly formatted
        let link = notification.link;

        notificationElement.innerHTML = `
                <div class="d-flex">
                    <div class="notification-icon">
                        <i class="${getNotificationIcon(notification.action)}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <h6 class="notification-title mb-1">${notification.title}</h6>
                            <small class="notification-time">${formatTime(notification.created_at)}</small>
                        </div>
                        <p class="notification-message mb-1">
                            <strong>${notification.user_name}</strong> ${notification.action}
                            <span class="notification-model">${notification.model}</span>
                        </p>
                        <div class="notification-actions d-flex justify-content-between align-items-center">
                            <a href="${link}" class="text-decoration-none text-primary" 
                               onclick="event.stopPropagation(); if(!${notification.is_read}) markAsRead(${notification.id});">
                                View details
                            </a>
                            ${notification.is_read==0 ? 
                                `<span class="mark-read-btn" onclick="event.stopPropagation(); markAsRead(${notification.id})">
                                    Mark as read
                                </span>` : 
                                `<small class="text-muted">
                                    <i class="fas fa-check-circle me-1"></i>Read
                                </small>`
                            }
                        </div>
                    </div>
                </div>
            `;

        // Add click handler for the entire notification
        notificationElement.addEventListener('click', (e) => {
          // Don't trigger if clicking on action buttons
          if (!e.target.closest('.notification-actions') &&
            !e.target.classList.contains('mark-read-btn')) {

            // Mark as read if unread
            if (!notification.is_read) {
              markAsRead(notification.id);
            }

            // Navigate to link
            setTimeout(() => {
              window.location.href = link;
            }, 100);
          }
        });

        container.appendChild(notificationElement);
      });
    }

    // Function to fetch notifications
    async function fetchNotifications() {
      try {
        const response = await fetch(`${BASE_URL}/notifications`, {
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        });

        if (!response.ok) {
          throw new Error('Network response was not ok');
        }

        const data = await response.json();
        currentNotifications = data.notifications || [];
        renderNotifications(currentNotifications);
      } catch (error) {
        console.error('Error fetching notifications:', error);
        // Show error in container
        const container = document.getElementById('notificationsContainer');
        if (container) {
          container.innerHTML = `
                    <div class="no-notifications">
                        <i class="fas fa-exclamation-triangle fa-3x mb-3 text-warning"></i>
                        <p class="mb-1">Failed to load notifications</p>
                        <small class="text-muted">Please try again later</small>
                    </div>
                `;
        }
      }
    }

    // Function to fetch just the unread count (optimized)
    async function fetchUnreadCount() {
      try {
        const response = await fetch(`${BASE_URL}/notifications/unread/count`, {
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        });

        if (response.ok) {
          const data = await response.json();
          updateNotificationBadge(data.count || 0);
        }
      } catch (error) {
        console.error('Error fetching unread count:', error);
      }
    }

    // Toast notification function
    function showToast(message, type = 'success') {
      // Create toast element
      const toast = document.createElement('div');
      toast.className = `toast align-items-center text-bg-${type === 'error' ? 'danger' : 'success'} border-0`;
      toast.setAttribute('role', 'alert');
      toast.setAttribute('aria-live', 'assertive');
      toast.setAttribute('aria-atomic', 'true');

      toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-${type === 'error' ? 'exclamation-circle' : 'check-circle'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;

      // Add to container
      const toastContainer = document.createElement('div');
      toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
      toastContainer.appendChild(toast);
      document.body.appendChild(toastContainer);

      // Show toast
      const bsToast = new bootstrap.Toast(toast);
      bsToast.show();

      // Remove after hidden
      toast.addEventListener('hidden.bs.toast', function() {
        document.body.removeChild(toastContainer);
      });
    }

    // Initialize when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
      // Initial fetch of notifications
      fetchNotifications();

      // Also fetch unread count immediately
      fetchUnreadCount();

      // Setup mark all as read button
      const markAllReadBtn = document.getElementById('markAllRead');
      if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', (e) => {
          e.preventDefault();
          e.stopPropagation();
          markAllAsRead();
        });
      }

      // Refresh notifications when dropdown is shown
      const notificationDropdown = document.getElementById('notificationDropdown');
      if (notificationDropdown) {
        notificationDropdown.addEventListener('shown.bs.dropdown', function() {
          fetchNotifications();
        });
      }

      // Auto-refresh unread count every 4 minutes
      setInterval(fetchUnreadCount, 240000);

      // Auto-refresh all notifications every 4 minutes
      setInterval(fetchNotifications, 240000);

      // Listen for visibility change (when user comes back to tab)
      document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
          fetchUnreadCount();
        }
      });
    });
  </script>


  <?= $this->renderSection('script') ?>
</body>

</html>