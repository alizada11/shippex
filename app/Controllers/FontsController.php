<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class FontsController extends BaseController
{
 public function select()
 {
  $apiKey = 'AIzaSyD2ngkDyD6vGqPkJQ4aoaAMPXoBa9RvUB4';
  $url = "https://www.googleapis.com/webfonts/v1/webfonts?key={$apiKey}";

  $fontsJson = file_get_contents($url);
  $data['fonts'] = json_decode($fontsJson, true)['items'] ?? [];
  $fontModel = new \App\Models\FontModel();
  $data['selected_fonts'] = $fontModel->findAll();

  return view('admin/settings/select_font', $data);
 }

 public function save()
 {
  $fontName = $this->request->getPost('font_name');

  $apiKey = 'AIzaSyD2ngkDyD6vGqPkJQ4aoaAMPXoBa9RvUB4';
  $url = "https://www.googleapis.com/webfonts/v1/webfonts?key={$apiKey}";

  $fontsJson = file_get_contents($url);
  $data['fonts'] = json_decode($fontsJson, true)['items'] ?? [];

  $fontModel = new \App\Models\FontModel();
  $fontModel->where('is_default', 1)->set(['is_default' => 0])->update();
  $fontModel->insert(['font_name' => $fontName, 'is_default' => 1]);
  $data['message'] = 'Font upddatedd!';
  $data['selected_fonts'] = $fontModel->findAll();
  return view('admin/settings/select_font', $data);
 }
 public function make_default($id)
 {
  $apiKey = 'AIzaSyD2ngkDyD6vGqPkJQ4aoaAMPXoBa9RvUB4';
  $url = "https://www.googleapis.com/webfonts/v1/webfonts?key={$apiKey}";

  $fontsJson = file_get_contents($url);
  $data['fonts'] = json_decode($fontsJson, true)['items'] ?? [];

  $fontModel = new \App\Models\FontModel();
  $fontModel->where('is_default', 1)->set(['is_default' => 0])->update();
  $fontModel->where('id', $id)->set(['is_default' => 1])->update();
  $data['message'] = 'Font upddated!';
  $data['latest_font'] = $fontModel
   ->orderBy('id', 'DESC')
   ->first();
  return view('admin/settings/select_font', $data);
 }
}
