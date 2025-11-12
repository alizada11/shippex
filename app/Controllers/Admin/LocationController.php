<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LocationModel;

class LocationController extends BaseController
{
 public function index()
 {
  $model = new LocationModel();
  $data['locations'] = $model->findAll();
  return view('admin/homepage/locations_index', $data);
 }

 public function create()
 {
  $title = 'Create a Location';
  return view('admin/homepage/locations_create', compact('title'));
 }

 public function store()
 {
  $model = new \App\Models\LocationModel();

  $data = [
   'name'          => $this->request->getPost('name'),
   'location_info' => $this->request->getPost('location_info'),
   'link'          => $this->request->getPost('link'),
   'status'        => $this->request->getPost('status'),
  ];

  // Handle file uploads
  $flag = $this->request->getFile('flag_image');
  if ($flag && $flag->isValid() && !$flag->hasMoved()) {
   $newName = $flag->getRandomName();
   $flag->move(FCPATH . 'uploads', $newName);
   $data['flag_image'] = $newName;
  }

  $thumb = $this->request->getFile('thumbnail_image');
  if ($thumb && $thumb->isValid() && !$thumb->hasMoved()) {
   $newName = $thumb->getRandomName();
   $thumb->move(FCPATH . 'uploads', $newName);
   $data['thumbnail_image'] = $newName;
  }

  $model->insert($data);

  return redirect()->to('admin/cms/locations')->with('success', 'Location added successfully');
 }

 public function update($id)
 {
  $model = new \App\Models\LocationModel();
  $location = $model->find($id);

  $data = [
   'name'          => $this->request->getPost('name'),
   'location_info' => $this->request->getPost('location_info'),
   'link'          => $this->request->getPost('link'),
   'status'        => $this->request->getPost('status'),
  ];

  // Handle file uploads
  $flag = $this->request->getFile('flag_image');
  if ($flag && $flag->isValid() && !$flag->hasMoved()) {
   $newName = $flag->getRandomName();
   $flag->move(FCPATH . 'uploads', $newName);
   $data['flag_image'] = $newName;
  }

  $thumb = $this->request->getFile('thumbnail_image');
  if ($thumb && $thumb->isValid() && !$thumb->hasMoved()) {
   $newName = $thumb->getRandomName();
   $thumb->move(FCPATH . 'uploads', $newName);
   $data['thumbnail_image'] = $newName;
  }

  $model->update($id, $data);

  return redirect()->to('admin/cms/locations')->with('success', 'Location updated successfully');
 }


 public function edit($id)
 {
  $model = new LocationModel();
  $data['location'] = $model->find($id);
  return view('admin/homepage/locations_edit', $data);
 }



 public function delete($id)
 {
  $model = new LocationModel();
  $model->delete($id);
  return redirect()->to('/admin/cms/locations')->with('success', 'Location deleted successfully');
 }
}
