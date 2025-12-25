<?php

/**
 * Model User - Quản lý người dùng
 */
class User extends Model
{
    protected $table = 'users';

    /**
     * Đăng ký user mới
     */
    public function register($username, $email, $password, $fullname)
    {
        // Kiểm tra username đã tồn tại
        if ($this->getUserByUsername($username)) {
            return ['success' => false, 'message' => 'Username đã tồn tại'];
        }

        // Kiểm tra email đã tồn tại
        if ($this->getUserByEmail($email)) {
            return ['success' => false, 'message' => 'Email đã tồn tại'];
        }

        // Mã hóa password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Tạo user mới
        $data = [
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword,
            'fullname' => $fullname,
            'role' => 'user'
        ];

        if ($this->create($data)) {
            return ['success' => true, 'message' => 'Đăng ký thành công'];
        }

        return ['success' => false, 'message' => 'Có lỗi xảy ra'];
    }

    /**
     * Đăng nhập
     */
    public function login($username, $password)
    {
        $user = $this->getUserByUsername($username);

        if (!$user) {
            return ['success' => false, 'message' => 'Username không tồn tại'];
        }

        if (!password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Mật khẩu không đúng'];
        }

        return ['success' => true, 'user' => $user];
    }

    /**
     * Lấy user theo username
     */
    public function getUserByUsername($username)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    /**
     * Lấy user theo email
     */
    public function getUserByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    /**
     * Cập nhật role của user
     */
    public function updateRole($userId, $role)
    {
        return $this->update($userId, ['role' => $role]);
    }

    /**
     * Lấy danh sách users với phân trang
     */
    public function getUsersPaginated($page = 1, $perPage = 20)
    {
        $offset = ($page - 1) * $perPage;

        $stmt = $this->db->prepare("
            SELECT id, username, email, fullname, role, created_at 
            FROM {$this->table} 
            ORDER BY created_at DESC 
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([(int)$perPage, (int)$offset]);

        return $stmt->fetchAll();
    }

    /**
     * Đếm tổng số users
     */
    public function getTotalUsers()
    {
        return $this->count();
    }
}
