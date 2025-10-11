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
        return view('admin/homepage/delivered_today_index', $data);
    }

    public function create()
    {
        return view('admin/homepage/delivered_today_create');
    }

    public function store()
    {
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
        return redirect()->to('/admin/cms/delivered-today')->with('success', 'Item added!');
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
        $data = $this->request->getPost();

        foreach (['courier_logo', 'retailer_logo', 'from_flag', 'to_flag'] as $field) {
            $file = $this->request->getFile($field);
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/delivered', $newName);
                $data[$field] = 'uploads/delivered/' . $newName;
            }
        }

        $model->update($id, $data);
        return redirect()->to('/admin/cms/delivered-today')->with('success', 'Item updated!');
    }

    public function delete($id)
    {
        $model = new DeliveredTodayModel();
        $model->delete($id);
        return redirect()->to('/admin/cms/delivered-today')->with('success', 'Item deleted!');
    }
}
