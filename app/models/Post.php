<?php

/**
 * Model Post - Quản lý bài viết
 */
class Post extends Model
{
    protected $table = 'posts';

    /**
     * Tạo slug từ tiêu đề
     */
    private function createSlug($title)
    {
        // Chuyển về chữ thường
        $slug = mb_strtolower($title, 'UTF-8');

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
     * Lọc HTML nguy hiểm, chỉ cho phép các thẻ an toàn
     */
    private function sanitizeHtml($content)
    {
        return strip_tags($content, ALLOWED_HTML_TAGS);
    }

    /**
     * Tạo excerpt từ content
     */
    private function createExcerpt($content, $length = 200)
    {
        $text = strip_tags($content);
        if (mb_strlen($text) > $length) {
            return mb_substr($text, 0, $length) . '...';
        }
        return $text;
    }

    /**
     * Tạo bài viết mới
     */
    public function createPost($title, $content, $userId, $categoryId = null, $tags = '')
    {
        $slug = $this->createSlug($title);
        $sanitizedContent = $this->sanitizeHtml($content);
        $excerpt = $this->createExcerpt($sanitizedContent);

        // Kiểm tra slug đã tồn tại chưa, nếu có thì thêm số
        $originalSlug = $slug;
        $counter = 1;
        while ($this->getBySlug($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $this->create([
            'title' => $title,
            'slug' => $slug,
            'content' => $sanitizedContent,
            'excerpt' => $excerpt,
            'user_id' => $userId,
            'category_id' => $categoryId,
            'tags' => $tags
        ]);
    }

    /**
     * Cập nhật bài viết
     */
    public function updatePost($id, $title, $content, $categoryId = null, $tags = '')
    {
        $sanitizedContent = $this->sanitizeHtml($content);
        $excerpt = $this->createExcerpt($sanitizedContent);

        return $this->update($id, [
            'title' => $title,
            'content' => $sanitizedContent,
            'excerpt' => $excerpt,
            'category_id' => $categoryId,
            'tags' => $tags
        ]);
    }

    /**
     * Lấy bài viết theo slug
     */
    public function getBySlug($slug)
    {
        $stmt = $this->db->prepare("
            SELECT p.*, u.username, u.fullname, c.name as category_name,
                   (SELECT COUNT(*) FROM comments WHERE post_id = p.id) as comment_count
            FROM {$this->table} p
            LEFT JOIN users u ON p.user_id = u.id
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.slug = ?
        ");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }

    /**
     * Lấy danh sách bài viết với phân trang
     */
    public function getPostsPaginated($page = 1, $perPage = POSTS_PER_PAGE)
    {
        $offset = ($page - 1) * $perPage;

        $stmt = $this->db->prepare("
            SELECT p.*, u.username, u.fullname, c.name as category_name,
                   (SELECT COUNT(*) FROM comments WHERE post_id = p.id) as comment_count
            FROM {$this->table} p
            LEFT JOIN users u ON p.user_id = u.id
            LEFT JOIN categories c ON p.category_id = c.id
            ORDER BY p.created_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$perPage, $offset]);

        return $stmt->fetchAll();
    }

    /**
     * Tìm kiếm bài viết
     */
    public function search($keyword, $page = 1, $perPage = POSTS_PER_PAGE)
    {
        $offset = ($page - 1) * $perPage;
        $searchTerm = "%{$keyword}%";

        $stmt = $this->db->prepare("
            SELECT p.*, u.username, u.fullname, c.name as category_name,
                   (SELECT COUNT(*) FROM comments WHERE post_id = p.id) as comment_count
            FROM {$this->table} p
            LEFT JOIN users u ON p.user_id = u.id
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.title LIKE ? OR p.tags LIKE ?
            ORDER BY p.created_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$searchTerm, $searchTerm, $perPage, $offset]);

        return $stmt->fetchAll();
    }

    /**
     * Đếm số bài viết khi tìm kiếm
     */
    public function countSearch($keyword)
    {
        $searchTerm = "%{$keyword}%";
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total 
            FROM {$this->table} 
            WHERE title LIKE ? OR tags LIKE ?
        ");
        $stmt->execute([$searchTerm, $searchTerm]);
        return $stmt->fetch()['total'];
    }

    /**
     * Tăng lượt xem
     */
    public function incrementViews($id)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET views = views + 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Lấy bài viết của một user
     */
    public function getPostsByUser($userId, $page = 1, $perPage = POSTS_PER_PAGE)
    {
        $offset = ($page - 1) * $perPage;

        $stmt = $this->db->prepare("
            SELECT p.*, c.name as category_name,
                   (SELECT COUNT(*) FROM comments WHERE post_id = p.id) as comment_count
            FROM {$this->table} p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.user_id = ?
            ORDER BY p.created_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$userId, $perPage, $offset]);

        return $stmt->fetchAll();
    }

    /**
     * Kiểm tra user có phải tác giả của bài viết không
     */
    public function isAuthor($postId, $userId)
    {
        $stmt = $this->db->prepare("SELECT user_id FROM {$this->table} WHERE id = ?");
        $stmt->execute([$postId]);
        $post = $stmt->fetch();

        return $post && $post['user_id'] == $userId;
    }
}
