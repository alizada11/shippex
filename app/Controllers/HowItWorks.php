<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\HowItWorksSectionModel;
use App\Models\HowItWorksStepModel;
use App\Models\WhyChooseModel;

class HowItWorks extends BaseController
{
 protected $sectionModel;
 protected $stepModel;
 protected $whyModel;

 public function __construct()
 {
  $this->sectionModel = new HowItWorksSectionModel();
  $this->stepModel    = new HowItWorksStepModel();
  $this->whyModel     = new WhyChooseModel();
 }

 // List all sections, steps, why-choose
 public function index()
 {
  $data['sections'] = $this->sectionModel->orderBy('id', 'ASC')->first();
  $data['steps']    = $this->stepModel->orderBy('id', 'ASC')->findAll();
  $data['why']      = $this->whyModel->orderBy('id', 'ASC')->findAll();
  $data['title'] = 'How it works';
  return view('how_it_works_index', $data);
 }

 public function admin_index()
 {
  $data['title'] = 'How it works';
  $data['section'] = $this->sectionModel->orderBy('id', 'ASC')->first();
  $data['steps']    = $this->stepModel->orderBy('id', 'ASC')->findAll();
  $data['why']      = $this->whyModel->orderBy('id', 'ASC')->findAll();
  // dd($data['section']);
  return view('admin/how_it_works/index', $data);
 }

 // Create section form
 public function create()
 {
  return view('admin/how_it_works/create');
 }

 // Store new section
 public function store()
 {
  $file = $this->request->getFile('image');
  $imageName = $file->getRandomName();
  $file->move(FCPATH . 'uploads/how_it_works', $imageName);

  $this->sectionModel->save([
   'title'       => $this->request->getPost('title'),
   'description' => $this->request->getPost('description'),
   'image'       => $imageName,
   'button_text' => $this->request->getPost('button_text'),
   'button_link' => $this->request->getPost('button_link')
  ]);

  return redirect()->to('/admin/how-it-works')->with('success', 'Item Created Successfully!');;
 }

 // Edit section form
 public function edit($id)
 {
  $data['section'] = $this->sectionModel->find($id);
  $data['title'] = 'Update Info';
  return view('admin/how_it_works/edit', $data);
 }

 // Update section
 public function update($id)
 {
  $section = $this->sectionModel->find($id);
  $file = $this->request->getFile('image');

  if ($file->isValid()) {
   $imageName = $file->getRandomName();
   $file->move(FCPATH . 'uploads', $imageName);
  } else {
   $imageName = $section['image'];
  }

  $this->sectionModel->update($id, [
   'title'       => $this->request->getPost('title'),
   'description' => $this->request->getPost('description'),
   'image'       => $imageName,
   'button_text' => $this->request->getPost('button_text'),
   'button_link' => $this->request->getPost('button_link'),
  ]);

  return redirect()->to('/admin/how-it-works')->with('success', 'Information Updated Successfully!');;
 }

 // Delete
 public function delete($id)
 {
  $this->sectionModel->delete($id);
  return redirect()->to('/admin/how-it-works')->with('success', 'Item Deleted Successfully!');;
 }
}
