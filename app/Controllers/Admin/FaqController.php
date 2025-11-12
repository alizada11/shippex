<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\FaqModel;

class FaqController extends BaseController
{
 protected $faqModel;

 public function __construct()
 {
  $this->faqModel = new FaqModel();
 }
 public function faqs()
 {
  $data['faqs'] = $this->faqModel->orderBy('id', 'ASC')->findAll();
  $data['title'] = 'FAQs List';
  return view('faq', $data);
 }
 // List all FAQs
 public function index()
 {
  helper('app');
  $data['faqs'] = $this->faqModel->orderBy('id', 'DESC')->paginate(12);
  $data['pager'] = $this->faqModel->pager;
  $data['title'] = 'FAQs List';
  return view('admin/faq/index', $data);
 }

 // Show create form
 public function create()
 {
  $title = 'Creat FAQ';
  return view('admin/faq/create', compact('title'));
 }

 // Store new FAQ
 public function store()
 {
  $this->faqModel->save([
   'question' => $this->request->getPost('question'),
   'answer' => $this->request->getPost('answer'),
  ]);

  return redirect()->to('/admin/faqs')->with('success', 'FAQ added successfully.');
 }

 // Show edit form
 public function edit($id)
 {
  $data['faq'] = $this->faqModel->find($id);
  return view('admin/faq/edit', $data);
 }

 // Update existing FAQ
 public function update($id)
 {
  $this->faqModel->update($id, [
   'question' => $this->request->getPost('question'),
   'answer' => $this->request->getPost('answer'),
  ]);

  return redirect()->to('/admin/faqs')->with('success', 'FAQ updated successfully.');
 }

 // Delete FAQ
 public function delete($id)
 {
  $this->faqModel->delete($id);
  return redirect()->to('/admin/faqs')->with('success', 'FAQ deleted successfully.');
 }
}
