<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\WhyChooseModel;
use FontLib\Table\Type\cmap;

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
  $data['title'] = 'List';
  return view('admin/why_choose/index', $data);
 }

 public function create()
 {
  $title = 'Create Item';
  return view('admin/why_choose/form', compact('title'));
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

  return redirect()->to('/admin/cms/why-choose')->with('success', 'Item Created Successfully!');;
 }

 public function edit($id)
 {
  $data['item'] = $this->whyModel->find($id);
  $data['title'] = 'Edit Item';
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

  return redirect()->to('/admin/cms/why-choose')->with('success', 'Information Updated Successfully!');;
 }

 public function delete($id)
 {
  $this->whyModel->delete($id);
  return redirect()->to('/admin/cms/why-choose')->with('success', 'Item Deleted Successfully!');;
 }
}
