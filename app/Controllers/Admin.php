<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ProfileModel;
use App\Models\TransactionModel;
use App\Models\CategoryModel;

class Admin extends BaseController
{
    protected UserModel        $userModel;
    protected ProfileModel     $profileModel;
    protected TransactionModel $transModel;
    protected CategoryModel    $catModel;

    public function __construct()
    {
        $this->userModel    = new UserModel();
        $this->profileModel = new ProfileModel();
        $this->transModel   = new TransactionModel();
        $this->catModel     = new CategoryModel();
    }

    // ─── USERS ────────────────────────────────────────────────────────────────
    public function users()
    {
        $users = $this->userModel->getAllWithProfiles();
        return view('admin/users', ['users' => $users]);
    }

    public function toggleUser(int $id)
    {
        $user = $this->userModel->find($id);
        if (! $user) {
            return $this->response->setJSON(['status' => 'error', 'message' => lang('App.not_found')]);
        }
        $newStatus = $user['is_active'] ? 0 : 1;
        $this->userModel->update($id, ['is_active' => $newStatus]);
        return $this->response->setJSON([
            'status'  => 'success',
            'message' => $newStatus ? lang('App.user_activated') : lang('App.user_deactivated'),
            'active'  => $newStatus,
        ]);
    }

    public function deleteUser(int $id)
    {
        if ($id === (int) session()->get('user_id')) {
            return $this->response->setJSON(['status' => 'error', 'message' => lang('App.cannot_delete_self')]);
        }
        $this->userModel->delete($id);
        return $this->response->setJSON(['status' => 'success', 'message' => lang('App.delete_success')]);
    }

    public function updateUser(int $id)
    {
        $user = $this->userModel->find($id);
        if (!$user) return $this->response->setJSON(['status' => 'error', 'message' => lang('App.not_found')]);

        $rules = [
            'username'  => "required|min_length[3]|max_length[50]|is_unique[users.username,id,{$id}]",
            'email'     => "required|valid_email|is_unique[users.email,id,{$id}]",
            'full_name' => "required|min_length[3]",
            'role'      => "required|in_list[admin,user]",
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['status' => 'error', 'message' => implode('<br>', $this->validator->getErrors())]);
        }

        $this->userModel->update($id, [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'role'     => $this->request->getPost('role'),
        ]);

        $this->profileModel->where('user_id', $id)->set([
            'full_name' => $this->request->getPost('full_name')
        ])->update();

        return $this->response->setJSON(['status' => 'success', 'message' => lang('App.save_success')]);
    }

    public function changePassword(int $id)
    {
        $user = $this->userModel->find($id);
        if (!$user) return $this->response->setJSON(['status' => 'error', 'message' => lang('App.not_found')]);

        $rules = [
            'password' => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['status' => 'error', 'message' => implode('<br>', $this->validator->getErrors())]);
        }

        $this->userModel->update($id, [
            'password' => $this->request->getPost('password')
        ]);

        return $this->response->setJSON(['status' => 'success', 'message' => lang('App.password_updated')]);
    }

    // ─── ALL TRANSACTIONS ─────────────────────────────────────────────────────
    public function transactions()
    {
        $filters = [
            'user_id' => $this->request->getGet('user_id'),
            'month'   => $this->request->getGet('month') ?? date('Y-m'),
        ];
        
        $userId       = $filters['user_id'] ? (int) $filters['user_id'] : null;
        $transactions = $this->transModel->getAllForAdmin($filters);
        $openingBalance = $this->transModel->getOpeningBalance($userId, $filters['month']);
        
        $users        = $this->userModel->findAll();
        
        return view('admin/transactions', [
            'transactions'   => $transactions,
            'openingBalance' => $openingBalance,
            'users'          => $users,
            'filters'        => $filters,
        ]);
    }

    // ─── CATEGORIES ───────────────────────────────────────────────────────────
    public function categories()
    {
        $categories = $this->catModel->getAllForAdmin();
        return view('admin/categories', ['categories' => $categories]);
    }

    public function storeCategory()
    {
        $rules = [
            'name'  => 'required|min_length[2]',
            'type'  => 'required|in_list[income,expense]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->catModel->insert([
            'user_id'   => null, // global
            'name'      => $this->request->getPost('name'),
            'type'      => $this->request->getPost('type'),
            'icon'      => $this->request->getPost('icon') ?? 'wallet-outline',
            'color'     => $this->request->getPost('color') ?? '#6366f1',
            'is_active' => 1,
        ]);

        return redirect()->to('/admin/categories')->with('success', lang('App.save_success'));
    }

    public function deleteCategory(int $id)
    {
        $this->catModel->delete($id);
        return $this->response->setJSON(['status' => 'success', 'message' => lang('App.delete_success')]);
    }
}
