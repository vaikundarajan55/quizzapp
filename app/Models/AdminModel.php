<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * AdminModel
 *
 * Admin panel users (table: admins). Passwords are stored with password_hash().
 */
class AdminModel extends Model
{
    protected $table         = 'admins';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['username', 'name', 'password_hash', 'last_login_at'];

    /**
     * Returns the admin row when the credentials match, otherwise null.
     */
    public function verifyLogin(string $username, string $password): ?array
    {
        $admin = $this->where('username', $username)->first();

        if (!$admin || !password_verify($password, $admin['password_hash'])) {
            return null;
        }

        if (password_needs_rehash($admin['password_hash'], PASSWORD_DEFAULT)) {
            $this->update($admin['id'], ['password_hash' => password_hash($password, PASSWORD_DEFAULT)]);
        }
        $this->update($admin['id'], ['last_login_at' => date('Y-m-d H:i:s')]);

        return $admin;
    }

    public function changePassword(int $id, string $newPassword): bool
    {
        return $this->update($id, ['password_hash' => password_hash($newPassword, PASSWORD_DEFAULT)]);
    }
}
