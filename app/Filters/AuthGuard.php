<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthGuard implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // If not logged in → redirect to login
        if (! $session->get('logged_in')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role'); // e.g., 'admin' or 'customer'

        // If specific role is required (passed from routes)
        if (! empty($arguments) && $role !== $arguments[0]) {
            // Optional: redirect based on their actual role
            if ($role === 'admin') {
                return redirect()->to('/dashboard');
            }
            if ($role === 'customer') {
                return redirect()->to('/customer/dashboard');
            }
            // Fallback: send to login if role unknown
            return redirect()->to('/login');
        }

        // Otherwise, allow request
        return;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No after logic
    }
}
