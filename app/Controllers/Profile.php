<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ProfileModel;

class Profile extends BaseController
{
    protected UserModel    $userModel;
    protected ProfileModel $profileModel;

    public function __construct()
    {
        $this->userModel    = new UserModel();
        $this->profileModel = new ProfileModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        $user   = $this->userModel->getUserWithProfile($userId);
        return view('profile/index', ['user' => $user]);
    }

    public function update()
    {
        $userId = session()->get('user_id');

        $rules = [
            'full_name'    => 'required|min_length[2]',
            'phone_number' => 'permit_empty|min_length[8]|max_length[20]',
            'email'        => 'required|valid_email|is_unique[users.email,id,' . $userId . ']',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle avatar upload
        $avatarPath = null;
        $file = $this->request->getFile('avatar');
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            if (! in_array($file->getMimeType(), $allowedTypes)) {
                return redirect()->back()->with('error', lang('App.invalid_file') . ' JPG, PNG, WebP.');
            }
            $newName    = $file->getRandomName();
            $file->move(FCPATH . 'uploads/avatars', $newName);
            $avatarPath = 'uploads/avatars/' . $newName;

            // Delete old avatar
            $oldProfile = $this->profileModel->getByUserId($userId);
            if ($oldProfile && $oldProfile['avatar'] && file_exists(FCPATH . $oldProfile['avatar'])) {
                unlink(FCPATH . $oldProfile['avatar']);
            }
        }

        // Update email in users table
        $this->userModel->update($userId, [
            'email' => $this->request->getPost('email'),
        ]);

        $profileData = [
            'full_name'             => $this->request->getPost('full_name'),
            'phone_number'          => $this->request->getPost('phone_number'),
            'address'               => $this->request->getPost('address'),
            'city'                  => $this->request->getPost('city'),
            'province'              => $this->request->getPost('province'),
            'postal_code'           => $this->request->getPost('postal_code'),
            'gender'                => $this->request->getPost('gender'),
            'date_of_birth'         => $this->request->getPost('date_of_birth') ?: null,
            'bio'                   => $this->request->getPost('bio'),
            'currency'              => $this->request->getPost('currency') ?? 'IDR',
            'monthly_income_target' => $this->request->getPost('monthly_income_target') ?? 0,
            'monthly_expense_limit' => $this->request->getPost('monthly_expense_limit') ?? 0,
        ];

        if ($avatarPath) {
            $profileData['avatar'] = $avatarPath;
            session()->set('avatar', $avatarPath);
        }

        $this->profileModel->upsert($userId, $profileData);

        session()->set([
            'full_name' => $profileData['full_name'],
            'email'     => $this->request->getPost('email'),
        ]);

        return redirect()->to('/profile')->with('success', lang('App.update_success'));
    }

    public function changePassword()
    {
        $userId = session()->get('user_id');
        $user   = $this->userModel->find($userId);

        $rules = [
            'old_password'     => 'required',
            'new_password'     => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        if (! password_verify($this->request->getPost('old_password'), $user['password'])) {
            return redirect()->back()->with('error', lang('App.password_incorrect'));
        }

        $this->userModel->skipValidation(true)->update($userId, [
            'password' => $this->request->getPost('new_password'),
        ]);

        return redirect()->to('/profile')->with('success', lang('App.password_success'));
    }
}
