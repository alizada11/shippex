<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\HowItWorksStepModel;

class Steps extends BaseController
{
 protected $stepModel;

 public function __construct()
 {
  $this->stepModel = new HowItWorksStepModel();
 }

 public function index()
 {
  $data['steps'] = $this->stepModel->orderBy('order', 'ASC')->findAll();
  return view('admin/steps/index', $data);
 }

 public function create()
 {
  return view('admin/steps/form');
 }

 public function store()
 {
  $file = $this->request->getFile('image');
  $imageName = $file->isValid() ? $file->getRandomName() : null;
  if ($imageName) $file->move(FCPATH . 'uploads', $imageName);

  $this->stepModel->save([
   'title'       => $this->request->getPost('title'),
   'description' => $this->request->getPost('description'),
   'image'       => $imageName,
   'order'       => $this->request->getPost('order'),
   'section_id' => 1
  ]);

  return redirect()->to('/admin/cms/steps');
 }

 public function edit($id)
 {
  $data['step'] = $this->stepModel->find($id);
  return view('admin/steps/form', $data);
 }

 public function update($id)
 {
  $step = $this->stepModel->find($id);
  $file = $this->request->getFile('image');
  $imageName = $file->isValid() ? $file->getRandomName() : $step['image'];
  if ($file->isValid()) $file->move(FCPATH . 'uploads', $imageName);

  $this->stepModel->update($id, [
   'title'       => $this->request->getPost('title'),
   'description' => $this->request->getPost('description'),
   'image'       => $imageName,
   'order'       => $this->request->getPost('order'),
   'section_id' => 1
  ]);

  return redirect()->to('/admin/cms/steps');
 }

 public function delete($id)
 {
  $this->stepModel->delete($id);
  return redirect()->to('/admin/how-it-works');
 }
}
