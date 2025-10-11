<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\HeroSectionModel;

class Home extends BaseController
{
 public function edit()
 {
  $heroModel = new HeroSectionModel();
  $hero = $heroModel->first();

  return view('admin/homepage/hero_edit', ['hero' => $hero]);
 }

 public function update()
 {
  $heroModel = new HeroSectionModel();
  $id = $this->request->getPost('id');

  $data = [
   'title'        => $this->request->getPost('title'),
   'subtitle'     => $this->request->getPost('subtitle'),
   'description'  => $this->request->getPost('description'),
   'button_text'  => $this->request->getPost('button_text'),
   'button_link'  => $this->request->getPost('button_link'),
  ];

  // handle optional image upload
  $file = $this->request->getFile('background_image');
  if ($file && $file->isValid() && !$file->hasMoved()) {
   $newName = $file->getRandomName();
   $file->move(FCPATH . 'images/', $newName);
   $data['background_image'] = 'images/' . $newName;
  }

  $heroModel->update($id, $data);

  return redirect()->back()->with('success', 'Hero section updated successfully!');
 }
}
