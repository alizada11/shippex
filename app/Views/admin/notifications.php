<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Notifications</h1>
                    <p class="text-muted mb-0">Manage your notifications and stay updated</p>
                </div>
                <div class="d-flex gap-2">
                    <?php if (!empty($notifications)): ?>
                        <button id="markAllReadBtn" class="btn btn-outline-primary">
                            <i class="fas fa-check-double me-2"></i>Mark All as Read
                        </button>
                        <button id="deleteAllBtn" class="btn btn-outline-danger">
                            <i class="fas fa-trash-alt me-2"></i>Clear All
                        </button>
                    <?php endif; ?>
                    <button id="refreshBtn" class="btn btn-outline-secondary">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('message')): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= session()->getFlashdata('message') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    <?php elseif (session()->getFlashdata('error')): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-0">Total</h6>
                            <h3 class="font-weight-bold mb-0"><?= $totalNotifications ?></h3>
                        </div>
                        <div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow">
                            <i class="fas fa-bell"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-0">Unread</h6>
                            <h3 class="font-weight-bold mb-0" id="unreadCount">
                                <?= count(array_filter($all, fn($n) => !$n['is_read'])) ?>
                            </h3>
                        </div>
                        <div class="icon icon-shape bg-gradient-warning text-white rounded-circle shadow">
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-0">Today</h6>
                            <h3 class="font-weight-bold mb-0" id="todayCount">
                                <?= count(array_filter($all, fn($n) => date('Y-m-d', strtotime($n['created_at'])) === date('Y-m-d'))) ?>
                            </h3>
                        </div>
                        <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-0">This Week</h6>
                            <h3 class="font-weight-bold mb-0" id="weekCount">
                                <?= count(array_filter($all, fn($n) => strtotime($n['created_at']) >= strtotime('-7 days'))) ?>
                            </h3>
                        </div>
                        <div class="icon icon-shape bg-gradient-success text-white rounded-circle shadow">
                            <i class="fas fa-calendar-week"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">All Notifications</h5>
                        <div class="d-flex gap-2">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-secondary active" data-filter="all">All</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" data-filter="unread">Unread</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" data-filter="read">Read</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($notifications)): ?>
                        <div class="table-responsive">
                            <div id="notification-list">
                                <?php
                                $groupedNotifications = [];
                                foreach ($notifications as $notification) {
                                    $date = date('Y-m-d', strtotime($notification['created_at']));
                                    $groupedNotifications[$date][] = $notification;
                                }
                                ?>

                                <?php foreach ($groupedNotifications as $date => $dayNotifications): ?>
                                    <div class="notification-day-group">
                                        <div class="day-header px-4 py-2 bg-light">
                                            <h6 class="mb-0 text-muted">
                                                <?= date('l, F j, Y', strtotime($date)) ?>
                                                <small class="ms-2 badge bg-secondary"><?= count($dayNotifications) ?></small>
                                            </h6>
                                        </div>

                                        <?php foreach ($dayNotifications as $notification): ?>
                                            <div class="notification-item border-bottom <?= $notification['is_read'] ? '' : 'notification-unread' ?>"
                                                data-id="<?= $notification['id'] ?>"
                                                data-read="<?= $notification['is_read'] ? 'true' : 'false' ?>">
                                                <div class="d-flex align-items-start p-2">
                                                    <!-- Notification Icon -->
                                                    <div class="notification-icon me-3">
                                                        <div class="icon-wrapper bg-<?= getNotificationColor($notification['action']) ?>">
                                                            <i class="<?= getNotificationIcon($notification['action']) ?>"></i>
                                                        </div>
                                                    </div>

                                                    <!-- Notification Content -->
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                                            <h6 class="mb-0"><?= esc($notification['title']) ?></h6>
                                                            <small class="text-muted">
                                                                <?= formatNotificationTime($notification['created_at']) ?>
                                                            </small>
                                                        </div>

                                                        <p class="text-muted mb-2">
                                                            <?= esc($notification['user_name']) ?>
                                                            <span class="text-primary"><?= esc($notification['action']) ?></span>
                                                            a <?= esc($notification['model']) ?>
                                                            <?php if ($notification['record_id']): ?>
                                                                <span class="badge bg-light text-dark ms-2">#<?= $notification['record_id'] ?></span>
                                                            <?php endif; ?>
                                                        </p>

                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div class="notification-actions">
                                                                <a href="<?= esc($notification['link']) ?>"
                                                                    class="btn btn-sm btn-outline-primary me-2">
                                                                    <i class="fas fa-external-link-alt me-1"></i>View Details
                                                                </a>
                                                                <?php if (!$notification['is_read']): ?>
                                                                    <button class="btn btn-sm btn-outline-success mark-as-read-btn me-2"
                                                                        data-id="<?= $notification['id'] ?>">
                                                                        <i class="fas fa-check me-1"></i>Mark as Read
                                                                    </button>
                                                                <?php endif; ?>
                                                                <button class="btn btn-sm btn-outline-danger delete-notification-btn"
                                                                    data-id="<?= $notification['id'] ?>">
                                                                    <i class="fas fa-trash me-1"></i>Delete
                                                                </button>
                                                            </div>
                                                            <div class="notification-status">
                                                                <?php if (!$notification['is_read']): ?>
                                                                    <span class="badge bg-warning">Unread</span>
                                                                <?php else: ?>
                                                                    <span class="badge bg-success">Read</span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-bell-slash fa-4x text-muted mb-4"></i>
                                <h4 class="text-muted">No notifications yet</h4>
                                <p class="text-muted mb-4">You're all caught up! Check back later for updates.</p>
                                <button id="refreshBtn2" class="btn btn-primary">
                                    <i class="fas fa-sync-alt me-2"></i>Refresh
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($notifications) && $pager): ?>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                Showing <?= $pager->getCurrentPage() * $pager->getPerPage() - $pager->getPerPage() + 1 ?>
                                to <?= min($pager->getCurrentPage() * $pager->getPerPage(), $pager->getTotal()) ?>
                                of <?= $pager->getTotal() ?> notifications
                            </div>
                            <div>
                                <?= $pager->links('default', 'bootstrap_full') ?>

                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Add these helper functions at the top of your view or in a helper -->
<?php
function getNotificationIcon($action)
{
    $icons = [
        'commented' => 'fas fa-comment',
        'approved' => 'fas fa-check-circle',
        'assigned' => 'fas fa-user-check',
        'created' => 'fas fa-plus-circle',
        'updated' => 'fas fa-edit',
        'deleted' => 'fas fa-trash',
        'default' => 'fas fa-bell'
    ];
    return $icons[$action] ?? $icons['default'];
}

function getNotificationColor($action)
{
    $colors = [
        'commented' => 'info',
        'approved' => 'success',
        'assigned' => 'primary',
        'created' => 'warning',
        'updated' => 'secondary',
        'deleted' => 'danger',
        'default' => 'dark'
    ];
    return $colors[$action] ?? $colors['default'];
}

function formatNotificationTime($dateString)
{
    $now = new DateTime();
    $date = new DateTime($dateString);
    $interval = $now->diff($date);

    if ($interval->days === 0) {
        if ($interval->h === 0) {
            if ($interval->i < 1) return 'Just now';
            return $interval->i . ' min ago';
        }
        return $interval->h . ' hour' . ($interval->h > 1 ? 's' : '') . ' ago';
    }

    if ($interval->days === 1) return 'Yesterday at ' . $date->format('h:i A');
    if ($interval->days < 7) return $interval->days . ' days ago';

    return $date->format('M j, Y \a\t h:i A');
}
?>

<style>
    /* Stats Cards Styling */
    .card-stats {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s, box-shadow 0.2s;
        background: #fff;
        border-left: 4px solid transparent;
        overflow: hidden;
    }

    .card-stats:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .card-stats .card-body {
        padding: 1.5rem;
    }

    .card-stats .card-title {
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .card-stats h3 {
        font-size: 1.75rem;
        font-weight: 700;
        margin-top: 0.5rem;
    }

    /* Icon styling */
    .card-stats .icon-shape {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* Remove rounded-circle if you want square icons, or keep for circle icons */
    .card-stats .icon-shape.rounded-circle {
        border-radius: 50% !important;
    }

    /* Gradient backgrounds */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .bg-gradient-warning {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .bg-gradient-info {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .bg-gradient-success {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }

    /* Alternative: Solid colors with hover effect */
    .bg-gradient-primary-alt {
        background: #0d6efd;
    }

    .bg-gradient-warning-alt {
        background: #ffc107;
    }

    .bg-gradient-info-alt {
        background: #0dcaf0;
    }

    .bg-gradient-success-alt {
        background: #198754;
    }

    /* Border colors for cards */
    .card-stats:nth-child(1) {
        border-left-color: #667eea;
    }

    .card-stats:nth-child(2) {
        border-left-color: #f5576c;
    }

    .card-stats:nth-child(3) {
        border-left-color: #4facfe;
    }

    .card-stats:nth-child(4) {
        border-left-color: #43e97b;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-stats {
            margin-bottom: 1rem;
        }

        .card-stats .card-body {
            padding: 1.25rem;
        }

        .card-stats h3 {
            font-size: 1.5rem;
        }

        .card-stats .icon-shape {
            width: 50px;
            height: 50px;
            font-size: 1.25rem;
        }
    }

    .notification-unread {
        background-color: rgba(13, 110, 253, 0.03);
        border-left: 4px solid #0d6efd !important;
    }

    .notification-item {
        transition: all 0.3s ease;
    }

    .notification-item:hover {
        background-color: rgba(0, 0, 0, 0.02) !important;
    }

    .notification-icon .icon-wrapper {
        width: 50px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }

    .notification-day-group {
        margin-bottom: 1rem;
    }

    .day-header {
        border-top: 1px solid #dee2e6;
        border-bottom: 1px solid #dee2e6;
    }

    .empty-state {
        padding: 3rem;
    }

    .empty-state i {
        opacity: 0.2;
    }

    /* Color classes for notification icons */
    .bg-info {
        background: linear-gradient(135deg, #17a2b8, #1abc9c);
    }

    .bg-success {
        background: linear-gradient(135deg, #28a745, #20c997);
    }

    .bg-primary {
        background: linear-gradient(135deg, #0d6efd, #6610f2);
    }

    .bg-warning {
        background: linear-gradient(135deg, #ffc107, #fd7e14);
    }

    .bg-secondary {
        background: linear-gradient(135deg, #6c757d, #495057);
    }

    .bg-danger {
        background: linear-gradient(135deg, #dc3545, #e83e8c);
    }

    .bg-dark {
        background: linear-gradient(135deg, #343a40, #212529);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .notification-actions {
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .notification-actions .btn {
            flex: 1;
            min-width: 120px;
        }


        .notification-icon {
            margin-bottom: 1rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mark as read button functionality
        document.querySelectorAll('.mark-as-read-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const notificationId = this.dataset.id;
                markAsRead(notificationId, this);
            });
        });

        // Delete notification button
        document.querySelectorAll('.delete-notification-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const notificationId = this.dataset.id;
                deleteNotification(notificationId, this);
            });
        });

        // Mark all as read
        const markAllReadBtn = document.getElementById('markAllReadBtn');
        if (markAllReadBtn) {
            markAllReadBtn.addEventListener('click', function() {
                if (confirm('Are you sure you want to mark all notifications as read?')) {
                    markAllAsRead();
                }
            });
        }

        // Delete all notifications
        const deleteAllBtn = document.getElementById('deleteAllBtn');
        if (deleteAllBtn) {
            deleteAllBtn.addEventListener('click', function() {
                if (confirm('Are you sure you want to delete all notifications? This action cannot be undone.')) {
                    deleteAllNotifications();
                }
            });
        }

        // Filter buttons
        document.querySelectorAll('[data-filter]').forEach(btn => {
            btn.addEventListener('click', function() {
                const filter = this.dataset.filter;

                // Update active button
                document.querySelectorAll('[data-filter]').forEach(b => {
                    b.classList.remove('active');
                });
                this.classList.add('active');

                // Filter notifications
                filterNotifications(filter);
            });
        });

        // Refresh buttons
        document.getElementById('refreshBtn')?.addEventListener('click', refreshNotifications);
        document.getElementById('refreshBtn2')?.addEventListener('click', refreshNotifications);

        // Functions
        async function markAsRead(notificationId, button) {
            try {
                const response = await fetch(`/notifications/read/${notificationId}`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Update UI
                    const notificationItem = button.closest('.notification-item');
                    notificationItem.classList.remove('notification-unread');
                    notificationItem.dataset.read = 'true';

                    // Update status badge
                    const statusBadge = notificationItem.querySelector('.notification-status .badge');
                    if (statusBadge) {
                        statusBadge.className = 'badge bg-success';
                        statusBadge.textContent = 'Read';
                    }

                    // Remove mark as read button
                    button.remove();

                    // Update unread count
                    updateUnreadCount();

                    showToast('Notification marked as read', 'success');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error marking notification as read', 'error');
            }
        }

        async function markAllAsRead() {
            try {
                const response = await fetch('/notifications/read-all', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Update all notifications in the list
                    document.querySelectorAll('.notification-unread').forEach(item => {
                        item.classList.remove('notification-unread');
                        item.dataset.read = 'true';

                        const statusBadge = item.querySelector('.notification-status .badge');
                        if (statusBadge) {
                            statusBadge.className = 'badge bg-success';
                            statusBadge.textContent = 'Read';
                        }

                        const markAsReadBtn = item.querySelector('.mark-as-read-btn');
                        if (markAsReadBtn) {
                            markAsReadBtn.remove();
                        }
                    });

                    // Update unread count
                    updateUnreadCount();

                    showToast('All notifications marked as read', 'success');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error marking all as read', 'error');
            }
        }

        async function deleteNotification(notificationId, button) {
            if (!confirm('Are you sure you want to delete this notification?')) {
                return;
            }

            try {
                const response = await fetch(`/notifications/delete/${notificationId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    const notificationItem = button.closest('.notification-item');
                    notificationItem.style.opacity = '0';
                    notificationItem.style.height = notificationItem.offsetHeight + 'px';

                    setTimeout(() => {
                        notificationItem.style.height = '0';
                        notificationItem.style.margin = '0';
                        notificationItem.style.padding = '0';
                        notificationItem.style.border = 'none';

                        setTimeout(() => {
                            notificationItem.remove();
                            updateStats();
                            checkEmptyState();
                        }, 300);
                    }, 200);

                    showToast('Notification deleted', 'success');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error deleting notification', 'error');
            }
        }

        async function deleteAllNotifications() {
            try {
                const response = await fetch('/notifications/delete-all', {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Remove all notifications
                    document.querySelectorAll('.notification-item').forEach(item => {
                        item.remove();
                    });

                    // Show empty state
                    checkEmptyState();

                    // Update stats
                    updateStats();

                    showToast('All notifications deleted', 'success');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error deleting all notifications', 'error');
            }
        }

        function filterNotifications(filter) {
            const allItems = document.querySelectorAll('.notification-item');

            allItems.forEach(item => {
                const isRead = item.dataset.read === 'true';

                switch (filter) {
                    case 'all':
                        item.style.display = '';
                        break;
                    case 'read':
                        item.style.display = isRead ? '' : 'none';
                        break;
                    case 'unread':
                        item.style.display = !isRead ? '' : 'none';
                        break;
                }
            });
        }

        function refreshNotifications() {
            window.location.reload();
        }

        function updateUnreadCount() {
            const unreadCount = document.querySelectorAll('.notification-unread').length;
            document.getElementById('unreadCount').textContent = unreadCount;
        }

        function updateStats() {
            const totalCount = document.querySelectorAll('.notification-item').length;
            const unreadCount = document.querySelectorAll('.notification-unread').length;
            const todayCount = document.querySelectorAll('.notification-item:has(.text-muted:contains("hour"))').length;
            const weekCount = document.querySelectorAll('.notification-item:has(.text-muted:contains("day"))').length;

            document.getElementById('unreadCount').textContent = unreadCount;
            // Update other counts as needed
        }

        function checkEmptyState() {
            const notificationList = document.getElementById('notification-list');
            if (notificationList.children.length === 0) {
                const emptyState = document.createElement('div');
                emptyState.className = 'text-center py-5';
                emptyState.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-bell-slash fa-4x text-muted mb-4"></i>
                    <h4 class="text-muted">No notifications found</h4>
                    <p class="text-muted mb-4">You're all caught up! Check back later for updates.</p>
                    <button onclick="refreshNotifications()" class="btn btn-primary">
                        <i class="fas fa-sync-alt me-2"></i>Refresh
                    </button>
                </div>
            `;
                notificationList.appendChild(emptyState);
            }
        }

        function showToast(message, type = 'success') {
            // Create toast element
            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-bg-${type === 'error' ? 'danger' : 'success'} border-0 position-fixed bottom-0 end-0 m-3`;
            toast.style.zIndex = '1060';
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

            document.body.appendChild(toast);

            // Show toast
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();

            // Remove after hidden
            toast.addEventListener('hidden.bs.toast', function() {
                document.body.removeChild(toast);
            });
        }

        // Auto refresh every 5 minutes
        setInterval(() => {
            const unreadCount = document.querySelectorAll('.notification-unread').length;
            if (unreadCount > 0) {
                // Update badge in navbar if exists
                const navbarBadge = document.querySelector('#notificationCount');
                if (navbarBadge) {
                    navbarBadge.textContent = unreadCount;
                }
            }
        }, 300000); // 5 minutes
    });
</script>

<?= $this->endSection() ?>