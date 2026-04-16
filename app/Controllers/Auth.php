<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ProfileModel;

class Auth extends BaseController
{
    protected UserModel $userModel;
    protected ProfileModel $profileModel;

    public function __construct()
    {
        $this->userModel    = new UserModel();
        $this->profileModel = new ProfileModel();
    }

    // ─── LOGIN HANDLING ───────────────────────────────────────────────────────

    /**
     * Display the login page.
     */
    public function login()
    {
        // If already logged in, redirect to dashboard
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    /**
     * Process the login attempt.
     */
    public function loginProcess()
    {
        // 1. Validation Rules
        $rules = [
            'login'    => 'required',
            'password' => 'required',
        ];

        // 2. Run Validation
        if (! $this->validate($rules)) {
            // Explicit redirect instead of back() to prevent session loop issues on Windows
            return redirect()->to('/auth/login')->withInput()->with('errors', $this->validator->getErrors());
        }

        // 3. Get Input
        $login    = $this->request->getPost('login');
        $password = $this->request->getPost('password');

        // 4. Find User (allow both email and username)
        $user = $this->userModel->findByEmail($login) ?? $this->userModel->findByUsername($login);

        // 5. Verify Credentials
        if (! $user || ! password_verify($password, $user['password'])) {
            return redirect()->to('/auth/login')->withInput()->with('error', lang('App.login_failed'));
        }

        // 6. Check Active Status
        if (! $user['is_active']) {
            return redirect()->to('/auth/login')->withInput()->with('error', lang('App.account_inactive'));
        }

        // 7. Setup Session Data
        $profile = $this->profileModel->getByUserId($user['id']);
        
        // Update last login timestamp
        $this->userModel->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);

        session()->set([
            'logged_in' => true,
            'user_id'   => $user['id'],
            'username'  => $user['username'],
            'email'     => $user['email'],
            'role'      => $user['role'],
            'full_name' => $profile['full_name'] ?? $user['username'],
            'avatar'    => $profile['avatar'] ?? null,
        ]);

        return redirect()->to('/dashboard')->with('success', lang('App.welcome_user', [($profile['full_name'] ?? $user['username'])]));
    }

    // ─── REGISTRATION HANDLING ────────────────────────────────────────────────

    /**
     * Display the registration page.
     */
    public function register()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }
        
        return view('auth/register');
    }

    /**
     * Process the registration attempt.
     */
    public function registerProcess()
    {
        // 1. Validation Rules
        $rules = [
            'full_name'        => 'required|min_length[3]',
            'username'         => 'required|min_length[3]|max_length[50]|is_unique[users.username]|alpha_numeric_punct',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'password'         => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
        ];

        $messages = [
            'username'         => ['is_unique' => lang('App.username') . ' ' . lang('App.already_have_account')], // Or just generic
            'email'            => ['is_unique' => lang('App.email') . ' ' . lang('App.already_have_account')],
            // 'confirm_password' => ['matches'   => 'Konfirmasi password tidak cocok dengan password di atas.'],
        ];

        // 2. Run Validation
        if (! $this->validate($rules, $messages)) {
            return redirect()->to('/auth/register')->withInput()->with('errors', $this->validator->getErrors());
        }

        // 3. Insert User
        $userId = $this->userModel->insert([
            'username'  => $this->request->getPost('username'),
            'email'     => $this->request->getPost('email'),
            'password'  => $this->request->getPost('password'), // Automatically hashed by UserModel hook
            'role'      => 'user',
            'is_active' => 1,
        ]);

        if (! $userId) {
            return redirect()->to('/auth/register')->withInput()->with('error', lang('App.register_failed'));
        }

        // 4. Insert Profile Data
        $this->profileModel->insert([
            'user_id'   => $userId,
            'full_name' => $this->request->getPost('full_name'),
            'currency'  => 'IDR', // Default currency for new users
        ]);

        return redirect()->to('/auth/login')->with('success', lang('App.register_success'));
    }

    // ─── LOGOUT ───────────────────────────────────────────────────────────────

    /**
     * Destroys the session and redirects to login.
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/login')->with('success', lang('App.logout_success'));
    }
}
