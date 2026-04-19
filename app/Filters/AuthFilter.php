<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! session()->get('logged_in')) {
            // Avoid redirect loop or unnecessary messages if already on login/register page
            $path = $request->getUri()->getPath();
            $path = ltrim($path, '/');
            
            // Allow access to login and register related routes
            $allowed = ['auth/login', 'auth/register', 'auth/loginProcess', 'auth/registerProcess'];
            if (in_array($path, $allowed)) {
                return;
            }

            return redirect()->to('/auth/login')->with('error', lang('App.not_logged_in'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // nothing
    }
}
