<?php

namespace App\Controllers;

use App\Models\CategoryModel;

class Category extends BaseController
{
    protected CategoryModel $catModel;

    public function __construct()
    {
        $this->catModel = new CategoryModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        // Get all categories for this user (both global and personal)
        $categories = $this->catModel->getForUser($userId);

        return view('category/index', [
            'title'      => lang('App.manage_personal_categories'),
            'categories' => $categories,
        ]);
    }

    public function store()
    {
        $rules = [
            'name'  => 'required|min_length[2]',
            'type'  => 'required|in_list[income,expense]',
        ];

        if (! $this->validate($rules)) {
            return $this->response->setJSON(['status' => 'error', 'message' => implode('<br>', $this->validator->getErrors())]);
        }

        $id = $this->catModel->insert([
            'user_id'   => session('user_id'), // Save as personal category
            'name'      => $this->request->getPost('name'),
            'type'      => $this->request->getPost('type'),
            'icon'      => $this->request->getPost('icon') ?? 'wallet-outline',
            'color'     => $this->request->getPost('color') ?? '#6366f1',
            'is_active' => 1,
        ]);

        $category = $this->catModel->find($id);

        return $this->response->setJSON([
            'status' => 'success', 
            'message' => lang('App.save_success'),
            'category' => $category
        ]);
    }

    public function update(int $id)
    {
        $category = $this->catModel->find($id);
        if (!$category || $category['user_id'] != session('user_id')) {
            return $this->response->setJSON(['status' => 'error', 'message' => lang('App.error')]);
        }

        $rules = [
            'name'  => 'required|min_length[2]',
            'type'  => 'required|in_list[income,expense]',
        ];

        if (! $this->validate($rules)) {
            return $this->response->setJSON(['status' => 'error', 'message' => implode('<br>', $this->validator->getErrors())]);
        }

        $this->catModel->update($id, [
            'name'  => $this->request->getPost('name'),
            'type'  => $this->request->getPost('type'),
            'icon'  => $this->request->getPost('icon') ?? 'wallet-outline',
            'color' => $this->request->getPost('color') ?? '#6366f1',
        ]);

        $category = $this->catModel->find($id);

        return $this->response->setJSON([
            'status' => 'success', 
            'message' => lang('App.save_success'),
            'category' => $category
        ]);
    }

    public function delete(int $id)
    {
        $category = $this->catModel->find($id);

        if (! $category) {
            return $this->response->setJSON(['status' => 'error', 'message' => lang('App.not_found')]);
        }

        // Prevent users from deleting global categories or categories belonging to other users
        if ($category['user_id'] != session('user_id')) {
            return $this->response->setJSON(['status' => 'error', 'message' => lang('App.error')]);
        }

        $this->catModel->delete($id);
        return $this->response->setJSON(['status' => 'success', 'message' => lang('App.delete_success')]);
    }
}
