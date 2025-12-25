<?php

/**
 * Model Category - Quản lý danh mục
 */
class Category extends Model
{
    protected $table = 'categories';

    /**
     * Tạo slug từ tên danh mục
     */
    private function createSlug($name)
    {
        // Chuyển về chữ thường
        $slug = mb_strtolower($name, 'UTF-8');

        // Chuyển ký tự có dấu thành không dấu
        $slug = preg_replace('/[àáạảãâầấậẩẫăằắặẳẵ]/u', 'a', $slug);
        $slug = preg_replace('/[èéẹẻẽêềếệểễ]/u', 'e', $slug);
        $slug = preg_replace('/[ìíịỉĩ]/u', 'i', $slug);
        $slug = preg_replace('/[òóọỏõôồốộổỗơờớợởỡ]/u', 'o', $slug);
        $slug = preg_replace('/[ùúụủũưừứựửữ]/u', 'u', $slug);
        $slug = preg_replace('/[ỳýỵỷỹ]/u', 'y', $slug);
        $slug = preg_replace('/đ/u', 'd', $slug);

        // Xóa ký tự đặc biệt
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        $slug = trim($slug, '-');

        return $slug;
    }

    /**
     * Thêm danh mục mới
     */
    public function createCategory($name)
    {
        $slug = $this->createSlug($name);

        return $this->create([
            'name' => $name,
            'slug' => $slug
        ]);
    }

    /**
     * Lấy danh mục theo slug
     */
    public function getBySlug($slug)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }
}
