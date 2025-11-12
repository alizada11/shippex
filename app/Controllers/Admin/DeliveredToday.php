<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DeliveredTodayModel;

class DeliveredToday extends BaseController
{
    public function index()
    {

        $model = new DeliveredTodayModel();
        $data['items'] = $model->findAll();
        $data['title'] = 'Deliverd';

        return view('admin/homepage/delivered_today_index', $data);
    }

    public function create()
    {
        $title = 'Create';
        return view('admin/homepage/delivered_today_create', compact('title'));
    }

    public function store()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'from_country' => 'required',
            'to_country' => 'required',
            'courier_logo' => 'uploaded[courier_logo]|max_size[courier_logo,2048]|is_image[courier_logo]',
            'retailer_logo' => 'uploaded[retailer_logo]|max_size[retailer_logo,2048]|is_image[retailer_logo]',
            'from_flag' => 'uploaded[from_flag]|max_size[from_flag,1024]|is_image[from_flag]',
            'to_flag' => 'uploaded[to_flag]|max_size[to_flag,1024]|is_image[to_flag]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $model = new DeliveredTodayModel();
        $data = $this->request->getPost();

        // Handle file uploads
        foreach (['courier_logo', 'retailer_logo', 'from_flag', 'to_flag'] as $field) {
            $file = $this->request->getFile($field);
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/delivered', $newName);
                $data[$field] = 'uploads/delivered/' . $newName;
            }
        }

        $model->insert($data);

        return redirect()->to('/admin/cms/delivered-today')->with('success', 'Item added successfully!');
    }


    public function edit($id)
    {
        $model = new DeliveredTodayModel();
        $data['item'] = $model->find($id);
        return view('admin/homepage/delivered_today_edit', $data);
    }

    public function update($id)
    {
        $model = new DeliveredTodayModel();
        $validation = \Config\Services::validation();

        $rules = [
            'from_country' => 'required',
            'to_country' => 'required',
            'courier_logo' => 'if_exist|max_size[courier_logo,2048]|is_image[courier_logo]',
            'retailer_logo' => 'if_exist|max_size[retailer_logo,2048]|is_image[retailer_logo]',
            'from_flag' => 'if_exist|max_size[from_flag,1024]|is_image[from_flag]',
            'to_flag' => 'if_exist|max_size[to_flag,1024]|is_image[to_flag]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $data = $this->request->getPost();
        $existing = $model->find($id);

        if (!$existing) {
            return redirect()->back()->with('error', 'Record not found.');
        }

        // Handle file uploads
        foreach (['courier_logo', 'retailer_logo', 'from_flag', 'to_flag'] as $field) {
            $file = $this->request->getFile($field);

            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $uploadPath = FCPATH . 'uploads/delivered/';
                $file->move($uploadPath, $newName);
                $data[$field] = 'uploads/delivered/' . $newName;

                // Remove old file
                if (!empty($existing[$field]) && file_exists(FCPATH . $existing[$field])) {
                    @unlink(FCPATH . $existing[$field]);
                }
            } else {
                // keep existing file if no new file uploaded
                $data[$field] = $existing[$field];
            }
        }

        $model->update($id, $data);

        return redirect()->to('/admin/cms/delivered-today')->with('success', 'Item updated successfully!');
    }


    public function delete($id)
    {
        $model = new DeliveredTodayModel();
        $model->delete($id);
        return redirect()->to('/admin/cms/delivered-today')->with('success', 'Item deleted!');
    }
}
