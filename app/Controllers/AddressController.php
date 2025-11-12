<?php

namespace App\Controllers;

use App\Models\AddressModel;

class AddressController extends BaseController
{
    protected $addressModel;

    public function __construct()
    {
        $this->addressModel = new AddressModel();
    }

    // List all addresses of logged-in user
    public function index()
    {
        $userId = session()->get('user_id');
        $data['shippingAddresses'] = $this->addressModel->where('user_id', $userId)->where('type', 'shipping')->findAll();
        $data['billingAddresses'] = $this->addressModel->where('user_id', $userId)->where('type', 'billing')->findAll();
        // dd($data);
        $data['title'] = 'My Addresses';
        return view('customers/addresses/index', $data);
    }

    // Show create form
    public function create()
    {
        helper('form');
        $data['title'] = 'Create Address';
        return view('customers/addresses/form', $data);
    }

    // Store new address
    public function store()
    {
        $userId = session()->get('user_id');
        $data = $this->request->getPost();
        $data['user_id'] = $userId;
        $data['is_default'] = 0;

        if (!$this->addressModel->insert($data)) {
            return redirect()->back()->withInput()->with('errors', $this->addressModel->errors());
        }

        return redirect()->to('/addresses')->with('success', 'Address created successfully.');
    }

    // Show edit form
    public function edit($id)
    {
        helper('form');
        $userId = session()->get('user_id');
        $data['address'] = $this->addressModel->where('id', $id)->where('user_id', $userId)->first();

        if (!$data['address']) {
            return redirect()->to('/addresses')->with('error', 'Address not found.');
        }
        $data['title'] = 'Edit Address';
        return view('customers/addresses/form', $data);
    }

    // Update address
    public function update($id)
    {
        $userId = session()->get('user_id');
        $address = $this->addressModel->where('id', $id)->where('user_id', $userId)->first();

        if (!$address) {
            return redirect()->to('/addresses')->with('error', 'Address not found.');
        }

        $data = $this->request->getPost();

        if (!$this->addressModel->update($id, $data)) {
            return redirect()->back()->withInput()->with('errors', $this->addressModel->errors());
        }

        return redirect()->to('/addresses')->with('success', 'Address updated successfully.');
    }



    public function setDefault($id)
    {

        $userId = session()->get('user_id');
        $address = $this->addressModel->where('id', $id)->where('user_id', $userId)->first();

        if (!$address) {
            return redirect()->to('/addresses')->with('error', 'Address not found.');
        }

        $updated = $this->addressModel->setDefault($userId, $address['type'], $id);
        if ($updated) {

            return redirect()->to('/addresses')->with('success', 'Default address updated.');
        } else {
            return redirect()->to('/addresses')->with('error', 'Error while updating address.');
        }
    }

    // Delete address
    public function delete($id)
    {
        $userId = session()->get('user_id');
        $address = $this->addressModel->where('id', $id)->where('user_id', $userId)->first();

        if (!$address) {
            return redirect()->to('/addresses')->with('error', 'Address not found.');
        }

        // Prevent deleting default address directly (optional)
        if ($address['is_default']) {
            return redirect()->to('/addresses')->with('error', 'Cannot delete the default address. Please set another address as default first.');
        }

        $this->addressModel->delete($id);
        return redirect()->to('/addresses')->with('success', 'Address deleted successfully.');
    }
}
