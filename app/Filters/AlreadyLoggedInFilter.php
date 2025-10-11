<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AlreadyLoggedInFilter implements FilterInterface
{
 public function before(RequestInterface $request, $arguments = null)
 {
  $session = session();
  $role = $session->get('role'); // You may adjust according to your auth logic

  if ($session->get('logged_in')) {
   // Redirect based on role
   if ($role === 'admin') {
    return redirect()->to('/dashboard');
   } elseif ($role === 'customer') {
    return redirect()->to('/customer/dashboard');
   } else {
    return redirect()->to('/');
   }
  }
 }

 public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
 {
  // Not used here
 }
}
