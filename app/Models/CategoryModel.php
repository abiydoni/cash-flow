<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table         = 'categories';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'user_id', 'name', 'type', 'icon', 'color', 'is_active',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get categories visible to a user: global (user_id=null) + user's own.
     */
    public function getForUser(int $userId, string $type = null): array
    {
        $builder = $this->where('is_active', 1)
            ->groupStart()
                ->where('user_id', null)
                ->orWhere('user_id', $userId)
            ->groupEnd();

        if ($type) {
            $builder->where('type', $type);
        }

        return $builder->orderBy('name', 'ASC')->findAll();
    }

    public function getAllForAdmin(): array
    {
        return $this->select('categories.*, IFNULL(users.username, "Global") AS owner')
            ->join('users', 'users.id = categories.user_id', 'left')
            ->orderBy('type', 'ASC')
            ->orderBy('name', 'ASC')
            ->findAll();
    }
}
