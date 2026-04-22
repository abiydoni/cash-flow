<?php

namespace App\Controllers;

use App\Models\DuesTypeModel;

class DuesType extends BaseController
{
    protected DuesTypeModel $model;

    public function __construct()
    {
        $this->model = new DuesTypeModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        $duesTypes = $this->model->where('user_id', $userId)->findAll();
        return view('duestype/index', [
            'title'     => 'Kelola Jenis Iuran',
            'duesTypes' => $duesTypes
        ]);
    }

    public function store()
    {
        $userId = session()->get('user_id');
        
        if (!$this->validate($this->model->getValidationRules())) {
            return $this->response->setJSON(['status' => 'error', 'message' => implode('<br>', $this->validator->getErrors())]);
        }

        $id = $this->request->getPost('id');
        
        // Security check for edit
        if ($id) {
            $existing = $this->model->where('id', $id)->where('user_id', $userId)->first();
            if (!$existing) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Akses ditolak']);
            }
        }

        $data = [
            'id'      => $id,
            'user_id' => $userId,
            'name'    => $this->request->getPost('name'),
            'amount'  => $this->request->getPost('amount'),
        ];

        $this->model->save($data);
        
        // Get the real ID if it was a new record
        $savedId = $id ?: $this->model->getInsertID();
        $savedType = $this->model->find($savedId);

        return $this->response->setJSON([
            'status'  => 'success', 
            'message' => lang('App.save_success'),
            'dtype'   => $savedType
        ]);
    }

    public function delete($id)
    {
        $userId = session()->get('user_id');
        $type = $this->model->where('id', $id)->where('user_id', $userId)->first();
        
        if (!$type) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Akses ditolak']);
        }

        $this->model->delete($id);
        return $this->response->setJSON(['status' => 'success', 'message' => lang('App.delete_success')]);
    }
}
