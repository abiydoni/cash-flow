<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfileModel extends Model
{
    protected $table         = 'profiles';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'user_id', 'full_name', 'phone_number', 'address', 'city',
        'province', 'postal_code', 'gender', 'date_of_birth',
        'avatar', 'bio', 'currency', 'monthly_income_target', 'monthly_expense_limit',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getByUserId(int $userId): ?array
    {
        return $this->where('user_id', $userId)->first();
    }

    public function upsert(int $userId, array $data): bool
    {
        $existing = $this->getByUserId($userId);
        if ($existing) {
            return $this->update($existing['id'], $data);
        }
        $data['user_id'] = $userId;
        return (bool) $this->insert($data);
    }
}
