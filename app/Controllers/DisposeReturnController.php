<?php

namespace App\Controllers;

use App\Models\DisposeReturnRequestModel;
use App\Models\PackageModel;
use CodeIgniter\HTTP\ResponseInterface;

class DisposeReturnController extends BaseController
{
  protected $requestModel;
  protected $packageModel;
  protected $db;

  public function __construct()
  {
    $this->requestModel = new DisposeReturnRequestModel();
    $this->packageModel = new PackageModel();
    $this->db = \Config\Database::connect();
  }

  /**
   * AJAX: return package details for an array of ids
   * POST body: { package_ids: [1,2,3] }
   */
  public function bulkInfo()
  {
    $input = $this->request->getJSON(true) ?? $this->request->getPost();
    $ids = $input['package_ids'] ?? ($this->request->getPost('package_ids') ?? []);
    if (!is_array($ids)) {
      // allow comma string
      $ids = is_string($ids) ? array_filter(explode(',', $ids)) : [];
    }

    if (empty($ids)) {
      return $this->response->setJSON(['success' => false, 'message' => 'No package IDs provided.']);
    }

    // load packages
    $packages = $this->packageModel->whereIn('id', $ids)->findAll();

    return $this->response->setJSON(['success' => true, 'packages' => $packages]);
  }

  /**
   * User: submit one or many requests
   * expects JSON or form POST:
   * package_ids[],
   * request_type[]  (values 'dispose'|'return' per package index),
   * reason[] (per package index)
   */
  public function disposeSubmit()
  {
    $input = $this->request->getJSON(true) ?? $this->request->getPost();

    $packageIds   = $input['package_ids']   ?? $this->request->getPost('package_ids');
    $requestTypes = $input['request_type']  ?? $this->request->getPost('request_type');
    $reasons      = $input['reason']        ?? $this->request->getPost('reason');

    if (!is_array($packageIds)) {
      $packageIds = is_string($packageIds) ? array_filter(explode(',', $packageIds)) : [];
    }
    if (!is_array($requestTypes)) {
      $requestTypes = is_string($requestTypes) ? array_filter(explode(',', $requestTypes)) : [];
    }
    if (!is_array($reasons)) {
      $reasons = is_string($reasons) ? array_filter(explode('||', $reasons)) : (array) $reasons;
    }

    if (empty($packageIds) || count($packageIds) !== count($requestTypes)) {
      return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
        ->setJSON(['success' => false, 'message' => 'Invalid payload. package_ids and request_type arrays must match.']);
    }

    $userId = session()->get('user_id');

    $inserted = 0;
    $errors = [];

    $this->db->transStart();
    try {
      foreach ($packageIds as $index => $pkgId) {
        $pkgId = (int) $pkgId;
        $type  = $requestTypes[$index] ?? null;
        $reason = $reasons[$index] ?? null;

        // basic server validation
        if (!in_array($type, ['disposed', 'returned'])) {
          $errors[] = "Invalid type for package {$pkgId}";
          continue;
        }
        // ensure package exists and belongs to user (unless admin)
        $package = $this->packageModel->find($pkgId);
        if (!$package) {
          $errors[] = "Package {$pkgId} not found.";
          continue;
        }
        // if customer, enforce ownership
        $role = session()->get('role');
        if ($role !== 'admin' && (int)$package['user_id'] !== (int)$userId) {
          $errors[] = "You do not own package {$pkgId}.";
          continue;
        }

        // skip if there's an open pending request for same package and type
        $existing = $this->requestModel->where('package_id', $pkgId)
          ->where('status', 'pending')
          ->first();
        if ($existing) {
          $errors[] = "<br>There is already a pending request for package {$pkgId}.";
          continue;
        }

        $this->requestModel->insert([
          'user_id' => $userId,
          'package_id' => $pkgId,
          'request_type' => $type,
          'reason' => trim($reason) ?: null,
          'status' => 'pending'
        ]);
        if ($type === 'disposed') {

          $this->packageModel->update($pkgId, ['status' => 'disposed']);
        }
        if ($type === 'returned') {

          $this->packageModel->update($pkgId, ['status' => 'returned']);
        }
        $inserted++;
      }

      $this->db->transComplete();
    } catch (\Throwable $e) {
      $this->db->transRollback();
      log_message('error', 'DisposeReturnController::submit error: ' . $e->getMessage());
      return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => 'Server error. Try again.']);
    }

    $message = "{$inserted} request(s) created.";
    if ($errors) $message .= ' Some items skipped: ' . implode(' ; ', $errors);

    // send email: A new combine repack request is created

    $title = 'You have a ' . $type . ' Request ';
    $actionDesc = 'created ';
    $modelName = "Return / Dispose ";
    $recordId = $inserted; // the inserted record ID
    $userName = session()->get('full_name');
    $adminLink = base_url("admin/dispose_return/edit/$recordId");

    send_admin_notification($actionDesc, $title, $modelName, $recordId, $userName, null, '', $adminLink);

    return $this->response->setJSON(['success' => true, 'message' => $message, 'inserted' => $inserted, 'errors' => $errors]);
  }

  /**
   * ADMIN: index page
   */
  public function adminIndex()
  {
    helper('app');

    $userRole = session('role');
    $userId   = session('user_id');

    $query = $this->requestModel->orderBy('created_at', 'DESC');

    // Apply filter only if customer
    if ($userRole === 'customer') {
      $query = $query->where('user_id', $userId);
    }

    $requests = $query->paginate(12);
    $pager    = $this->requestModel->pager;

    return view('admin/dispose_return/index', [
      'requests' => $requests,
      'title'    => 'Return/Dispose Requests',
      'pager'    => $pager,
    ]);
  }

  /**
   * ADMIN: process (approve/reject)
   * POST: { status: 'approved'|'rejected' }
   */
  public function process($id)
  {
    $id = (int)$id;
    $input = $this->request->getJSON(true) ?? $this->request->getPost();
    $status = $input['status'] ?? $this->request->getPost('status');

    if (!in_array($status, ['approved', 'rejected'])) {
      return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
        ->setJSON(['success' => false, 'message' => 'Invalid status.']);
    }

    $req = $this->requestModel->find($id);
    if (!$req) {
      return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Request not found.']);
    }
    if ($req['status'] !== 'pending') {
      return $this->response->setJSON(['success' => false, 'message' => 'Request already processed.']);
    }

    $this->db->transStart();
    try {
      $this->requestModel->update($id, ['status' => $status, 'admin_id' => session()->get('user_id')]);

      if ($status === 'approved') {
        // update package status
        $pkg = $this->packageModel->find($req['package_id']);
        if ($pkg) {
          $newStatus = ($req['request_type'] === 'dispose') ? 'disposed' : 'returned';

          // prevent invalid transition
          if (!in_array($pkg['status'], ['disposed', 'returned'])) {

            $this->packageModel->update($pkg['id'], ['status' => $newStatus]);
          } else {
            // already disposed/returned; still mark request approved
            log_message('warning', "Package {$pkg['id']} already has status {$pkg['status']}");
          }
        }
      }

      $this->db->transComplete();

      $title = ' a ' . $req['request_type'] . ' Request has been' . $status;
      $actionDesc = 'updated';
      $modelName = "Return / Dispose ";
      $recordId = $id; // the inserted record ID
      $userName = session()->get('full_name');
      $adminLink = base_url("admin/dispose_return/edit/$recordId");

      send_admin_notification($actionDesc, $title, $modelName, $recordId, $userName, null, '', $adminLink);

      return $this->response->setJSON(['success' => true, 'message' => 'Request processed']);
    } catch (\Throwable $e) {
      $this->db->transRollback();
      log_message('error', 'DisposeReturnController::process error: ' . $e->getMessage());
      return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => 'Server error.']);
    }
  }

  /**
   * ADMIN: delete a request
   */
  public function delete($id)
  {
    $id = (int)$id;
    $req = $this->requestModel->find($id);
    if (!$req) {
      return redirect()->back()->with('error', 'Request not found.');
    }
    $this->requestModel->delete($id);
    return redirect()->back()->with('success', 'Request deleted.');
  }

  public function edit($id)
  {
    $req = $this->requestModel->find($id);
    if (!$req) {
      return redirect()->back()->with('error', 'Request not found.');
    }
    return view('admin/dispose_return/edit', ['request' => $req, 'title' => 'Edit Dispose/Return Request']);
  }
  public function update($id)
  {
    $req = $this->requestModel->find($id);
    if (!$req) {
      return redirect()->back()->with('error', 'Request not found.');
    }


    // Only allow editing if pending
    if ($req['status'] !== 'pending') {
      return redirect()->back()->with('error', 'Only pending requests can be updated.');
    }
    $packageId = $req['package_id'];

    $data = $this->request->getPost();

    // Simple validation
    $rules = [
      'request_type' => 'required|in_list[disposed,returned]',
      'reason'       => 'permit_empty|min_length[3]',
    ];

    if (! $this->validate($rules)) {
      return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $updateData = [
      'request_type' => $data['request_type'],
      'reason'       => $data['reason'] ?? null,
      'status' => $this->request->getPost('status')
    ];

    $update = $this->requestModel->update($id, $updateData);
    if ($update) {
      $this->packageModel->update($packageId, ['archive' => 1]);
    }
    return redirect()->to('/admin/dispose-return')->with('success', 'Request updated successfully.');
  }
}
