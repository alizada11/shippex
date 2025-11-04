<?php

namespace App\Controllers;

class Download extends BaseController
{
 /**
  * Download current page as PDF/HTML
  */
 public function page()
 {
  $data = $this->request->getGet();

  $filename = $data['filename'] ?? 'document_' . date('Y-m-d_H-i-s');
  $format = $data['format'] ?? 'html';
  $content = $data['content'] ?? '';

  // Clean filename
  $filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $filename);

  switch ($format) {
   case 'pdf':
    return $this->downloadAsPDF($content, $filename);
   case 'html':
   default:
    return $this->downloadAsHTML($content, $filename);
  }
 }

 /**
  * Download warehouse details specifically
  */
 public function warehouse($warehouseId = null)
 {
  // Load your warehouse model
  $warehouseModel = new \App\Models\WarehouseModel();
  $warehouse = $warehouseModel->find($warehouseId);

  if (!$warehouse) {
   return redirect()->back()->with('error', 'Warehouse not found.');
  }

  $filename = "warehouse_{$warehouse['city']}_{$warehouse['country']}_" . date('Y-m-d');
  $filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $filename);

  // You can create a custom view for warehouse downloads
  $html = view('customers/warehouses/download_template', [
   'warehouse' => $warehouse,
   'download_date' => date('Y-m-d H:i:s')
  ]);

  return $this->downloadAsPDF($html, $filename);
 }

 private function downloadAsHTML($content, $filename)
 {
  return $this->response->download(
   $filename . '.html',
   $content
  );
 }

 private function downloadAsPDF($content, $filename)
 {
  $dompdf = new \Dompdf\Dompdf();
  $dompdf->loadHtml($content);
  $dompdf->setPaper('A4', 'portrait');
  $dompdf->render();

  $this->response->setHeader('Content-Type', 'application/pdf');
  $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '.pdf"');

  return $this->response->setBody($dompdf->output());
 }
}
