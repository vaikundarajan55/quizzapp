<?php

namespace App\Controllers;

use App\Models\AdminModel;
use CodeIgniter\Controller;

class AuthController extends Controller
{
    public function login()
    {
        if (session('admin_id')) {
            return redirect()->to(base_url('admin/dashboard'));
        }
        return view('admin/login');
    }

    public function attemptLogin()
    {
        $username = trim((string) $this->request->getPost('username'));
        $password = (string) $this->request->getPost('password');

        $admin = $username !== '' ? (new AdminModel())->verifyLogin($username, $password) : null;

        if (!$admin) {
            return redirect()->to(base_url('admin/login'))
                ->withInput()
                ->with('error', 'Invalid username or password.');
        }

        $session = session();
        $session->regenerate(true); // new session id on login
        $session->set([
            'admin_id'       => (int) $admin['id'],
            'admin_name'     => $admin['name'] ?: $admin['username'],
            'admin_username' => $admin['username'],
        ]);

        return redirect()->to(base_url('admin/dashboard'))
            ->with('success', 'Welcome back, ' . ($admin['name'] ?: $admin['username']) . '!');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('admin/login'));
    }
}
