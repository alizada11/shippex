<?php

namespace App\Controllers;

use App\Models\WarehouseModel;
use CodeIgniter\Controller;

class Warehouse extends Controller
{
 protected $warehouseModel;

 public function __construct()
 {
  $this->warehouseModel = new WarehouseModel();
  helper(['form', 'url']);
 }

 public function index()
 {
  $data['warehouses'] = $this->warehouseModel->findAll();
  return view('admin/warehouse_pages/index', $data);
 }

 public function create()
 {
  return view('admin/warehouse_pages/create');
 }

 public function store()
 {
  $data = $this->request->getPost();
  $code = $this->request->getPost('country_code');

  // Get country name from JSON
  $countries = json_decode(file_get_contents(APPPATH . 'Views/partials/countries.json'), true);
  $countryName = '';
  foreach ($countries as $ct) {
   if ($ct['code'] === $code) {
    $countryName = $ct['name'];
    break;
   }
  }

  $data['country_code'] = $code;
  $data['country_name'] = $countryName;


  // Handle image uploads
  $banner = $this->request->getFile('banner_image');
  if ($banner && $banner->isValid()) {
   $data['banner_image'] = $banner->getRandomName();
   $banner->move('uploads/warehouses', $data['banner_image']);
  }

  $hero = $this->request->getFile('hero_image');
  if ($hero && $hero->isValid()) {
   $data['hero_image'] = $hero->getRandomName();
   $hero->move('uploads/warehouses', $data['hero_image']);
  }

  $brands = $this->request->getFile('brands_image');
  if ($brands && $brands->isValid()) {
   $data['brands_image'] = $brands->getRandomName();
   $brands->move('uploads/warehouses', $data['brands_image']);
  }

  $this->warehouseModel->insert($data);
  return redirect()->to('/admin/w_pages')->with('message', 'Warehouse created successfully');
 }

 public function edit($id)
 {
  $data['warehouse'] = $this->warehouseModel->find($id);
  return view('admin/warehouse_pages/edit', $data);
 }

 public function update($id)
 {
  $data = $this->request->getPost();
  $code = $this->request->getPost('country_code');

  // Get country name from JSON
  $countries = json_decode(file_get_contents(APPPATH . 'Views/partials/countries.json'), true);
  $countryName = '';
  foreach ($countries as $ct) {
   if ($ct['code'] === $code) {
    $countryName = $ct['name'];
    break;
   }
  }

  $data['country_code'] = $code;
  $data['country_name'] = $countryName;

  // handle images only if re-uploaded
  $warehouse = $this->warehouseModel->find($id);

  foreach (['banner_image', 'hero_image', 'brands_image'] as $imgField) {
   $file = $this->request->getFile($imgField);
   if ($file && $file->isValid()) {
    $data[$imgField] = $file->getRandomName();
    $file->move('uploads/warehouses', $data[$imgField]);
   } else {
    $data[$imgField] = $warehouse[$imgField]; // keep old one
   }
  }

  $this->warehouseModel->update($id, $data);
  return redirect()->to('/admin/w_pages')->with('message', 'Warehouse updated successfully');
 }

 public function show($countryCode)
 {
  $data['warehouse'] = $this->warehouseModel
   ->where('country_code', strtoupper($countryCode))
   ->first();

  if (!$data['warehouse']) {
   throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
  }

  return view('warehouses/show', $data);
 }
}
