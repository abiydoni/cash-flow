<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table         = 'app_settings';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['key', 'value'];
    protected $useTimestamps = true;

    public function getVal(string $key, $default = '')
    {
        $setting = $this->where('key', $key)->first();
        return $setting ? $setting['value'] : $default;
    }

    public function setVal(string $key, string $value)
    {
        $setting = $this->where('key', $key)->first();
        if ($setting) {
            return $this->update($setting['id'], ['value' => $value]);
        }
        return $this->insert(['key' => $key, 'value' => $value]);
    }

    public function getAllSettings(): array
    {
        $settings = $this->findAll();
        $map = [];
        foreach ($settings as $s) {
            $map[$s['key']] = $s['value'];
        }
        return $map;
    }
}
