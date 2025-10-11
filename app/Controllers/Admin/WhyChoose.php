<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\WhyChooseModel;

class WhyChoose extends BaseController
{
 protected $whyModel;

 public function __construct()
 {
  $this->whyModel = new WhyChooseModel();
 }

 public function index()
 {
  $data['items'] = $this->whyModel->orderBy('order', 'ASC')->findAll();
  return view('admin/why_choose/index', $data);
 }

 public function create()
 {
  return view('admin/why_choose/form');
 }

 public function store()
 {
  $file = $this->request->getFile('icon');
  $iconName = $file->isValid() ? $file->getRandomName() : null;
  if ($iconName) $file->move(FCPATH . 'uploads/why_choose', $iconName);

  $this->whyModel->save([
   'title'       => $this->request->getPost('title'),
   'description' => $this->request->getPost('description'),
   'icon'        => $iconName,
   'order'       => $this->request->getPost('order')
  ]);

  return redirect()->to('/admin/cms/why-choose');
 }

 public function edit($id)
 {
  $data['item'] = $this->whyModel->find($id);
  return view('admin/why_choose/form', $data);
 }

 public function update($id)
 {
  $item = $this->whyModel->find($id);
  $file = $this->request->getFile('icon');
  $iconName = $file->isValid() ? $file->getRandomName() : $item['icon'];
  if ($file->isValid()) $file->move(FCPATH . 'uploads/why_choose', $iconName);

  $this->whyModel->update($id, [
   'title'       => $this->request->getPost('title'),
   'description' => $this->request->getPost('description'),
   'icon'        => $iconName,
   'order'       => $this->request->getPost('order')
  ]);

  return redirect()->to('/admin/cms/why-choose');
 }

 public function delete($id)
 {
  $this->whyModel->delete($id);
  return redirect()->to('/admin/why-choose');
 }
}
