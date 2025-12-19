<?php

namespace App\Controllers;

use App\Models\NotificationModel;
use CodeIgniter\Controller;

class NotificationController extends Controller
{


  // Add this method to your existing index method to get all notifications for the page
  public function all()
  {
    $notificationModel = new NotificationModel();

    $totalNotifications = $notificationModel->countAllResults();
    $all = $notificationModel->findAll();

    // Get all notifications with pagination
    $perPage = 10;
    $notifications = $notificationModel
      ->orderBy('created_at', 'DESC')
      ->paginate($perPage);
    $pager = $notificationModel->pager;

    return view('admin/notifications', [
      'notifications' => $notifications,
      'totalNotifications' => $totalNotifications,
      'pager' => $pager,
      'title' => 'All Notifications',
      'all' => $all
    ]);
  }


  /**
   * Show all notifications (unread and read)
   */
  public function index()
  {
    // Fetch notifications from the database (last 24 hours as an example)
    $notificationModel = new NotificationModel();

    // Get notifications created in the last 24 hours
    // $last24Hours = date('Y-m-d H:i:s', strtotime('-24 hours'));

    // Get notifications from the database
    // $notifications = $notificationModel
    //  ->where('created_at >', $last24Hours)
    //  ->findAll();
    $notifications = $notificationModel
      ->orderBy('created_at', 'DESC')->where('is_read', 0)->findAll();

    // Transform the notifications to the required structure
    $formattedNotifications = array_map(function ($notification) {
      return [
        'id' => $notification['id'],
        'title' => $this->getNotificationTitle($notification),
        'action' => $notification['action'],
        'model' => $notification['model'],
        'record_id' => $notification['record_id'],
        'user_name' => $notification['user_name'],
        'user_email' => $notification['user_email'],
        'link' => $notification['link'],
        'is_read' => $notification['is_read'],
        'created_at' => $notification['created_at']
      ];
    }, $notifications);

    // Return the notifications as a JSON response
    return $this->response->setJSON(['notifications' => $formattedNotifications]);
  }

  private function getNotificationTitle($notification)
  {
    // Custom title based on the action/model
    switch ($notification['action']) {
      case 'commented':
        return 'New comment on your ' . $notification['model'];
      case 'approved':
        return $notification['model'] . ' approved';
      case 'assigned':
        return 'Task assigned to you';
      case 'uploaded':
        return 'New file uploaded';
      case 'created':
        return $notification['model'] . ' created';
      case 'updated':
        return $notification['model'] . ' updated';
      case 'deleted':
        return $notification['model'] . ' deleted';
      case 'payment':
        return $notification['model'] . ' updated';
      default:
        return 'New notification';
    }
  }

  /**
   * Mark a notification as read
   */
  public function markAsRead($id)
  {
    $notificationModel = new NotificationModel();

    try {
      // Find and update the notification
      $notification = $notificationModel->find($id);

      if (!$notification) {
        return $this->response->setJSON([
          'success' => false,
          'message' => 'Notification not found'
        ]);
      }

      $notificationModel->update($id, ['is_read' => true]);

      return $this->response->setJSON([
        'success' => true,
        'message' => 'Notification marked as read'
      ]);
    } catch (\Exception $e) {
      return $this->response->setJSON([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
      ]);
    }
  }

  // Mark all notifications as read for current user
  public function markAllAsRead()
  {
    $notificationModel = new NotificationModel();

    try {
      // In a real app, you would filter by current user
      // For now, marking all notifications as read
      $notificationModel->where('is_read', false)
        ->set(['is_read' => true])
        ->update();

      return $this->response->setJSON([
        'success' => true,
        'message' => 'All notifications marked as read'
      ]);
    } catch (\Exception $e) {
      return $this->response->setJSON([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
      ]);
    }
  }

  /**
   * Get unread notifications for bell icon
   */
  public function getUnreadNotifications()
  {
    $notificationModel = new NotificationModel();

    // Get unread notifications
    $notifications = $notificationModel->where('is_read', 0)->findAll();

    return $this->response->setJSON([
      'notifications' => $notifications
    ]);
  }
  // Delete single notification
  public function delete($id)
  {
    $notificationModel = new NotificationModel();

    try {
      $notification = $notificationModel->find($id);

      if (!$notification) {
        return $this->response->setJSON([
          'success' => false,
          'message' => 'Notification not found'
        ]);
      }

      $notificationModel->delete($id);

      return $this->response->setJSON([
        'success' => true,
        'message' => 'Notification deleted successfully'
      ]);
    } catch (\Exception $e) {
      return $this->response->setJSON([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
      ]);
    }
  }

  // Delete all notifications
  public function deleteAll()
  {
    $notificationModel = new NotificationModel();

    try {
      $notificationModel->truncate();

      return $this->response->setJSON([
        'success' => true,
        'message' => 'All notifications deleted successfully'
      ]);
    } catch (\Exception $e) {
      return $this->response->setJSON([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
      ]);
    }
  }


  public function getUnreadCount()
  {
    $notificationModel = new NotificationModel();

    $count = $notificationModel
      ->where('is_read', false)
      ->countAllResults();

    return $this->response->setJSON(['count' => $count]);
  }
}
