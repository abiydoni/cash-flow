<?php

namespace App\Controllers;

use App\Models\MemberModel;

class Member extends BaseController
{
    protected MemberModel $model;

    public function __construct()
    {
        $this->model = new MemberModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        $members = $this->model->where('user_id', $userId)->findAll();
        return view('member/index', [
            'title'   => 'Kelola Anggota',
            'members' => $members
        ]);
    }

    public function store()
    {
        $userId = session()->get('user_id');
        
        if (!$this->validate($this->model->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $id = $this->request->getPost('id');
        
        // Security check for edit
        if ($id) {
            $existing = $this->model->where('id', $id)->where('user_id', $userId)->first();
            if (!$existing) {
                return redirect()->to('/member')->with('error', 'Akses ditolak');
            }
        }

        $this->model->save([
            'id'        => $id,
            'user_id'   => $userId,
            'name'      => $this->request->getPost('name'),
            'join_date' => $this->request->getPost('join_date'),
            'is_active' => $this->request->getPost('is_active') ?? 1,
        ]);

        return redirect()->to('/member')->with('success', lang('App.save_success'));
    }

    public function delete($id)
    {
        $userId = session()->get('user_id');
        $member = $this->model->where('id', $id)->where('user_id', $userId)->first();
        
        if (!$member) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Akses ditolak']);
        }

        $this->model->delete($id);
        return $this->response->setJSON(['status' => 'success', 'message' => lang('App.delete_success')]);
    }

    public function toggle($id)
    {
        $userId = session()->get('user_id');
        $member = $this->model->where('id', $id)->where('user_id', $userId)->first();
        
        if (!$member) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Akses ditolak']);
        }

        $newActive = $member['is_active'] ? 0 : 1;
        $this->model->update($id, ['is_active' => $newActive]);

        return $this->response->setJSON([
            'status' => 'success',
            'active' => $newActive,
            'message' => lang('App.save_success')
        ]);
    }
}
