<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\VirtualAddressModel;
use CodeIgniter\HTTP\ResponseInterface;

class ProfileController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $user_id = session()->get('user_id');


        $data['profile'] = $userModel->where('id', $user_id)->first();

        return view('customers/profile/index', $data);
    }

    public function addresses()
    {
        $warehouses = new VirtualAddressModel();

        $data['warehouses'] = $warehouses->findAll();

        return view('customers/profile/warehouse_addresses', $data);
    }
}
