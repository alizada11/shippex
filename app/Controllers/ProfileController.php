<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\VirtualAddressModel;
use App\Models\WarehouseRequestModel;
use CodeIgniter\HTTP\ResponseInterface;

class ProfileController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $user_id = session()->get('user_id');


        $data['profile'] = $userModel->where('id', $user_id)->first();
        $data['title'] = 'Your Profile';
        return view('customers/profile/index', $data);
    }

    public function addresses()
    {
        $warehouses = new VirtualAddressModel();
        $userId = session()->get('user_id'); // adjust if you're using a different session key
        $warehouseRequestModel = new warehouseRequestModel();
        $pending = $warehouseRequestModel->where('user_id', $userId)
            ->where('status', 'pending')
            ->findAll();
        $pendingMap = [];
        foreach ($pending as $req) {
            $pendingMap[$req['warehouse_id']] = $req['created_at'];
        }
        $data['pendingMap'] = $pendingMap;
        $userRequests = $warehouseRequestModel
            ->where('user_id', $userId)
            ->findAll();
        $requestMap = [];
        foreach ($userRequests as $req) {
            $requestMap[$req['warehouse_id']] = $req; // contains status, is_default, rejectation_reason
        }

        $data['requestMap'] = $requestMap;
        $data['warehouses'] = $warehouses->findAll();
        $data['title'] = 'warehouse addresses';
        return view('customers/profile/warehouse_addresses', $data);
    }
}
