<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class SearchController extends Controller
{

 public function index()
 {
  helper(['global_search', 'global_search_helper']); // Load both helpers

  $query     = trim($this->request->getPost('q'));
  $model     = $this->request->getPost('model');
  $detailUrl = $this->request->getPost('detail_url');
  $backUrl   = $this->request->getPost('back_url');

  if (!$query || !$model) {
   return redirect()->back()->with('error', 'Search query missing');
  }

  // Store context
  session()->set([
   'search_model'       => $model,
   'search_detail_url'  => $detailUrl,
   'search_back_url'    => $backUrl,
  ]);

  $results = global_search($model, $query);

  // Determine model type for display
  $modelTypes = [
   \App\Models\UserModel::class => 'user',
   \App\Models\PackageModel::class => 'package',
   \App\Models\ShippingBookingModel::class => 'shipping',
   \App\Models\ShopperRequestModel::class => 'shopper_request',
   \App\Models\WarehouseRequestModel::class => 'warehouse_request',
   \App\Models\CombineRepackRequestModel::class => 'combine_request',
   \App\Models\DisposeReturnRequestModel::class => 'dispose_request'
  ];

  $model_type = $modelTypes[$model] ?? 'unknown';

  return view('admin/search_results', [
   'query'      => esc($query),
   'results'    => $results,
   'detail_url' => $detailUrl,
   'back_url'   => $backUrl,
   'model_type' => $model_type,
   'title'      => 'Search Results'
  ]);
 }

 /**
  * AJAX real-time search
  */
 public function live()
 {
  helper('global_search');

  $query = trim($this->request->getGet('q'));
  $model = session('search_model');
  $detailUrl = session('search_detail_url');

  log_message('info', 'Live search query: ' . $query . ' for model: ' . $model);

  if (!$query || !$model) {
   log_message('info', 'Live search missing query or model');
   return $this->response->setJSON([
    'results' => [],
    'detail_url' => $detailUrl ?? '/admin'
   ]);
  }

  $results = global_search($model, $query, 20);

  log_message('info', 'Live search found ' . count($results) . ' results');

  return $this->response->setJSON([
   'results' => $results,
   'detail_url' => $detailUrl ?? '/admin'
  ]);
 }
}
