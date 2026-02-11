<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('is_login')) {
            return redirect()->to('login');
        }

        $role = session()->get('role');
        $uri = $request->getUri();
        $segment = $uri->getSegment(1);

        if ($segment == 'admin' && $role !== 'admin') {
            return redirect()->to($role . '/dashboard');
        }

        if ($segment == 'guru' && $role !== 'guru') {
            return redirect()->to($role . '/dashboard');
        }

        if ($segment == 'siswa' && $role !== 'siswa') {
            return redirect()->to($role . '/dashboard');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}