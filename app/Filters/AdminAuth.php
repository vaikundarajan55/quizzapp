<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * AdminAuth
 *
 * Blocks admin routes unless an admin is logged in.
 */
class AdminAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session('admin_id')) {
            return null;
        }

        // AJAX calls (reorder, toggle) get JSON instead of a redirect
        if ($request->isAJAX()) {
            return service('response')->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Session expired. Please log in again.']);
        }

        return redirect()->to(base_url('admin/login'))
            ->with('error', 'Please log in to continue.');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
