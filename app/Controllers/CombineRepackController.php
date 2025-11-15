<?php

namespace App\Controllers;

use App\Models\CombineRepackRequestModel;
use App\Models\PackageModel;
use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class CombineRepackController extends BaseController
{
    protected $requestModel;
    protected $packageModel;

    public function __construct()
    {
        $this->requestModel = new CombineRepackRequestModel();
        $this->packageModel = new PackageModel();
    }

    /* ----------------------------------------------
     * USER: Submit Combine & Repack Request
     * ---------------------------------------------- */
    public function submitRequest()
    {
        $data = $this->request->getJSON(true);

        // ✅ Validate data
        if (empty($data['package_ids']) || !is_array($data['package_ids']) || empty($data['warehouse_id'])) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid request data. Please select packages and a warehouse.'
            ]);
        }

        // ✅ Retrieve packages
        $packages = $this->packageModel->whereIn('id', $data['package_ids'])->findAll();
        if (empty($packages)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'No valid packages found for combination.'
            ]);
        }
        if (!empty($packages)) {
            // Extract unique user_ids from the selected packages
            $userIds = array_unique(array_column($packages, 'user_id'));

            if (count($userIds) > 1) {
                // More than one unique user_id found
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Cannot combine packages from different users.'
                ]);
            }

            // ✅ Calculate totals
            $totals = ['weight' => 0, 'length' => 0, 'width' => 0, 'height' => 0];
            foreach ($packages as $p) {
                $totals['weight'] += (float) $p['weight'];
                $totals['length'] += (float) $p['length'];
                $totals['width']  += (float) $p['width'];
                $totals['height'] += (float) $p['height'];
            }

            // ✅ Save combine request
            $requestId = $this->requestModel->insert([
                'user_id'       => session()->get('user_id'),
                'package_ids'   => json_encode($data['package_ids']),
                'warehouse_id'  => $data['warehouse_id'],
                'total_weight'  => $totals['weight'],
                'total_length'  => $totals['length'],
                'total_width'   => $totals['width'],
                'total_height'  => $totals['height'],
                'status'        => 'pending',
            ]);
            foreach ($packages as $pkg) {
                $p_id = $pkg['id'];
                $this->packageModel->update($p_id, ['status' => 'combined']);
            }
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Combine & Repack request submitted successfully.',
                'request_id' => $requestId
            ]);
        }
    }

    /* ----------------------------------------------
     * ADMIN: List Requests
     * ---------------------------------------------- */
    public function listRequests()
    {
        helper('app');
        $session = session();
        $role = $session->get('role');
        $user_id = $session->get('user_id');
        if ($role === 'customer') {

            $data['requests'] = $this->requestModel->where('user_id', $user_id)->orderBy('created_at', 'DESC')->paginate(12);
        } else {

            $data['requests'] = $this->requestModel->orderBy('created_at', 'DESC')->paginate(12);
        }
        $data['pager'] = $this->requestModel->pager;
        $data['title'] = 'Comibnation Requests';
        return view('admin/combine_requests/index', $data);
    }

    /* ----------------------------------------------
     * ADMIN: Edit Request
     * ---------------------------------------------- */
    public function editRequest($id)
    {
        $request = $this->requestModel->find($id);
        if (!$request) {
            throw new PageNotFoundException('Combine & Repack Request not found.');
        }

        $packages = $this->packageModel
            ->whereIn('id', json_decode($request['package_ids'], true))
            ->findAll();
        $title = 'Edit Combination Request';
        return view('admin/combine_requests/edit', compact('request', 'packages', 'title'));
    }

    /* ----------------------------------------------
     * ADMIN: Update Request & (Optional) Create Package
     * ---------------------------------------------- */

    public function updateRequest($id)
    {
        $data = $this->request->getPost();

        // Validate required fields
        if (empty($data['weight']) || empty($data['length']) || empty($data['width']) || empty($data['height'])) {
            return redirect()->back()->with('error', 'All package dimensions and weight are required.');
        }

        // Update request record
        $this->requestModel->update($id, [
            'total_weight'  => $data['weight'],
            'total_length'  => $data['length'],
            'total_width'   => $data['width'],
            'total_height'  => $data['height'],
            'status'        => 'completed',
            'admin_id'      => session()->get('user_id'),
            'updated_at'    => date('Y-m-d H:i:s')
        ]);

        // If admin checked the "Create Package" box
        if (!empty($data['create_package']) && $data['create_package'] == '1') {

            // Validate that we have a user and warehouse
            if (empty($data['user_id']) || empty($data['warehouse_id'])) {
                log_message('error', 'CombineRepackController: Missing user_id or warehouse_id.');
                return redirect()->back()->with('error', 'User and warehouse ID are required to create a package.');
            }

            $packageData = [
                'user_id'       => $data['user_id'],
                'virtual_address_id'  => $data['warehouse_id'],
                'weight'        => $data['weight'],
                'length'        => $data['length'],
                'width'         => $data['width'],
                'height'        => $data['height'],
                'status'        => 'ready',
                'combined_from' => $id,
                'tracking_number' => 'PENDING-' . strtoupper(uniqid()),
                'created_at'    => date('Y-m-d H:i:s'),
            ];

            // Try inserting
            $inserted = $this->packageModel->insert($packageData);
            if ($inserted) {
                $email = \Config\Services::email();

                // Prepare data for email

                $userModel = new UserModel();

                // Fetch main request
                $request = $this->packageModel->find($inserted);
                $userName = $userModel->find($data['user_id'])['firstname'] . ' ' . $userModel->find($data['user_id'])['lastname'];
                $data = [
                    'request' => $request, // the $request row
                    'userName' => $userName ?? 'Customer'
                ];

                $message = view('emails/notify_combined', $data);

                $email->setTo($userModel->find($request['user_id'])['email']);
                $email->setSubject('Your Request #' . $request['id'] . ' is Waiting for Purchase Invoice');
                $email->setMessage($message);
                $email->setMailType('html'); // Important

                if (!$email->send()) {
                    log_message('error', $email->printDebugger(['headers']));
                    return redirect()->back()->with('error', 'There has been an error while sending email.');
                }
            }
            if (!$inserted) {
                // Get error from model
                $dbError = $this->packageModel->errors();
                log_message('error', 'CombineRepackController: Failed to create package. Errors: ' . json_encode($dbError));
                return redirect()->back()->with('error', 'Failed to create package. Check logs for details.');
            }

            log_message('debug', 'CombineRepackController: New package created with ID=' . $inserted);
        } else {
            log_message('debug', 'CombineRepackController: Package creation skipped by admin.');
        }

        return redirect()->to('/admin/combine-requests')->with('success', 'Request processed successfully.');
    }

    public function deleteRequest($id)
    {
        // Fetch the request first
        $request = $this->requestModel->find($id);

        if (!$request) {
            return redirect()->back()->with('error', 'Request not found.');
        }

        // Decode packages for clarity
        $packageIds = json_decode($request['package_ids'], true);

        // Optional: check if a combined package was created
        $combinedPackage = $this->packageModel
            ->where('combined_from', $id)
            ->first();

        // Try deleting everything safely
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Delete the combined package if exists
            if ($combinedPackage) {
                $this->packageModel->delete($combinedPackage['id']);
                log_message('debug', "CombineRepackController: Deleted combined package ID={$combinedPackage['id']}");
            }

            // Delete the request itself
            $this->requestModel->delete($id);
            log_message('debug', "CombineRepackController: Deleted combine request ID={$id}");

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Database transaction failed.');
            }

            return redirect()->to('/admin/combine-requests')
                ->with('success', 'Combine & Repack request (and related package if any) deleted successfully.');
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'CombineRepackController: Failed to delete request ' . $id . ' — ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete the request. Check logs for details.');
        }
    }
}
