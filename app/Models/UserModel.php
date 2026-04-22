<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'username', 'email', 'password', 'role', 'is_active', 'last_login',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [];
    protected $validationMessages = [];

    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data): array
    {
        if (isset($data['data']['password']) && ! empty($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_BCRYPT);
        }
        return $data;
    }

    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)->first();
    }

    public function findByUsername(string $username): ?array
    {
        return $this->where('username', $username)->first();
    }

    public function getUserWithProfile(int $userId): ?array
    {
        return $this->select('users.*, profiles.full_name, profiles.phone_number,
                profiles.address, profiles.city, profiles.province,
                profiles.postal_code, profiles.gender, profiles.date_of_birth,
                profiles.avatar, profiles.bio, profiles.currency,
                profiles.monthly_income_target, profiles.monthly_expense_limit')
            ->join('profiles', 'profiles.user_id = users.id', 'left')
            ->where('users.id', $userId)
            ->first();
    }

    public function getAllWithProfiles(): array
    {
        return $this->select('users.*, profiles.full_name, profiles.city')
            ->join('profiles', 'profiles.user_id = users.id', 'left')
            ->findAll();
    }
}
