<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PromoCardModel;

class PromoCards extends BaseController
{
 protected $promoModel;

 public function __construct()
 {
  $this->promoModel = new PromoCardModel();
 }

 public function index()
 {
  $data['promoCards'] = $this->promoModel->findAll();
  return view('admin/homepage/promo_cards_index', $data);
 }

 public function create()
 {
  return view('admin/homepage/promo_cards_create');
 }

 public function store()
 {
  $validation = $this->validate([
   'title' => 'required|max_length[255]',
   'description' => 'required',
   'button_text' => 'required|max_length[100]',
   'button_url' => 'required|valid_url',
   'image' => 'uploaded[image]|is_image[image]|max_size[image,2048]',
   'background' => 'uploaded[background]|is_image[background]|max_size[background,2048]',
  ]);

  if (!$validation) {
   return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
  }

  // Handle file upload
  $file = $this->request->getFile('image');
  $filename = null;
  if ($file && $file->isValid()) {
   $filename = $file->getRandomName();
   $file->move(FCPATH . 'uploads/promo_cards/', $filename);
  }
  $fileBg = $this->request->getFile('background');
  if ($fileBg && $fileBg->isValid()) {
   $filenameBg = $fileBg->getRandomName();
   $fileBg->move(FCPATH . 'uploads/promo_cards/', $filenameBg);
   $data['background'] = $filenameBg;
  }

  $this->promoModel->save([
   'title' => $this->request->getPost('title'),
   'description' => $this->request->getPost('description'),
   'button_text' => $this->request->getPost('button_text'),
   'button_url' => $this->request->getPost('button_url'),
   'image' => $filename,
   'background' => $filenameBg
  ]);

  return redirect()->to('/admin/cms/promo-cards')->with('success', 'Promo card added.');
 }

 public function edit($id)
 {
  $data['card'] = $this->promoModel->find($id);
  return view('admin/homepage/promo_cards_edit', $data);
 }

 public function update($id)
 {
  $validation = $this->validate([
   'title' => 'required|max_length[255]',
   'description' => 'required',
   'button_text' => 'required|max_length[100]',
   'button_url' => 'required|valid_url',
   'image' => 'is_image[image]|max_size[image,2048]',
  ]);

  if (!$validation) {
   return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
  }

  $data = [
   'title' => $this->request->getPost('title'),
   'description' => $this->request->getPost('description'),
   'button_text' => $this->request->getPost('button_text'),
   'button_url' => $this->request->getPost('button_url')
  ];

  $file = $this->request->getFile('image');
  if ($file && $file->isValid()) {
   $filename = $file->getRandomName();
   $file->move(FCPATH . 'uploads/promo_cards/', $filename);
   $data['image'] = $filename;
  }
  $fileBg = $this->request->getFile('background');
  if ($fileBg && $fileBg->isValid()) {
   $filenameBg = $fileBg->getRandomName();
   $fileBg->move(FCPATH . 'uploads/promo_cards/', $filenameBg);
   $data['background'] = $filenameBg;
  }

  $this->promoModel->update($id, $data);

  return redirect()->to('/admin/cms/promo-cards')->with('success', 'Promo card updated.');
 }

 public function delete($id)
 {
  $this->promoModel->delete($id);
  return redirect()->to('/admin/cms/promo-cards')->with('success', 'Promo card deleted.');
 }
}
