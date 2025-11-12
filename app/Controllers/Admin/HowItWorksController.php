<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\HowItWorksModel;

class HowItWorksController extends BaseController
{
 public function index()
 {
  $model = new HowItWorksModel();
  $steps = $model->orderBy('step_number')->findAll();
  $title = 'How it work';
  return view('admin/homepage/how_it_works_index', compact('steps', 'title'));
 }

 public function create()
 {
  $data['title'] = 'Create Step';
  return view('admin/homepage/how_it_works_create', $data);
 }

 public function store()
 {
  $model = new HowItWorksModel();

  $data = [
   'step_number' => $this->request->getPost('step_number'),
   'subtitle'    => $this->request->getPost('subtitle'),
   'title'       => $this->request->getPost('title'),
   'description' => $this->request->getPost('description'),
  ];

  // handle icon upload
  $file = $this->request->getFile('icon');
  if ($file && $file->isValid() && !$file->hasMoved()) {
   $newName = $file->getRandomName();
   $file->move(FCPATH . 'uploads/icons/', $newName);
   $data['icon'] = 'uploads/icons/' . $newName;
  }

  $model->insert($data);

  return redirect()->to('/admin/cms/how-it-works')->with('success', 'Step created');
 }

 public function edit($id)
 {

  $model = new HowItWorksModel();
  $step = $model->find($id);
  $title = 'Edit Step';
  return view('admin/homepage/how_it_works_edit', compact('step', 'title'));
 }

 public function update($id)
 {
  $model = new HowItWorksModel();

  $data = [
   'step_number' => $this->request->getPost('step_number'),
   'subtitle'    => $this->request->getPost('subtitle'),
   'title'       => $this->request->getPost('title'),
   'description' => $this->request->getPost('description'),
  ];

  $file = $this->request->getFile('icon');
  if ($file && $file->isValid() && !$file->hasMoved()) {
   $newName = $file->getRandomName();
   $file->move(FCPATH . 'uploads/icons/', $newName);
   $data['icon'] = 'uploads/icons/' . $newName;
  }

  $model->update($id, $data);

  return redirect()->to('/admin/cms/how-it-works')->with('success', 'Step updated');
 }

 public function delete($id)
 {

  $model = new HowItWorksModel();
  $model->delete($id);

  return redirect()->to('/admin/cms/how-it-works')->with('success', 'Step deleted');
 }
}
