<?php

/**
 * Model Comment - Quản lý bình luận (hỗ trợ phân cấp)
 */
class Comment extends Model
{
    protected $table = 'comments';

    /**
     * Tạo bình luận mới
     */
    public function createComment($postId, $content, $userId = null, $name = null, $email = null, $parentId = 0)
    {
        $data = [
            'post_id' => $postId,
            'parent_id' => $parentId,
            'content' => htmlspecialchars($content, ENT_QUOTES, 'UTF-8'),
            'user_id' => $userId,
            'name' => $name ? htmlspecialchars($name, ENT_QUOTES, 'UTF-8') : null,
            'email' => $email ? htmlspecialchars($email, ENT_QUOTES, 'UTF-8') : null
        ];

        return $this->create($data);
    }

    /**
     * Lấy tất cả bình luận của một bài viết (đã sắp xếp phân cấp)
     */
    public function getCommentsByPost($postId)
    {
        $stmt = $this->db->prepare("
            SELECT c.*, u.username, u.fullname
            FROM {$this->table} c
            LEFT JOIN users u ON c.user_id = u.id
            WHERE c.post_id = ?
            ORDER BY c.created_at ASC
        ");
        $stmt->execute([$postId]);
        $comments = $stmt->fetchAll();

        // Tổ chức comments thành cây phân cấp
        return $this->buildCommentTree($comments);
    }

    /**
     * Xây dựng cây bình luận phân cấp
     */
    private function buildCommentTree($comments, $parentId = 0)
    {
        $tree = [];

        foreach ($comments as $comment) {
            if ($comment['parent_id'] == $parentId) {
                $comment['replies'] = $this->buildCommentTree($comments, $comment['id']);
                $tree[] = $comment;
            }
        }

        return $tree;
    }

    /**
     * Đếm số bình luận của một bài viết
     */
    public function countByPost($postId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table} WHERE post_id = ?");
        $stmt->execute([$postId]);
        return $stmt->fetch()['total'];
    }

    /**
     * Lấy tất cả bình luận (cho admin)
     */
    public function getAllComments($page = 1, $perPage = COMMENTS_PER_PAGE)
    {
        $offset = ($page - 1) * $perPage;

        $stmt = $this->db->prepare("
            SELECT c.*, p.title as post_title, p.slug as post_slug,
                   u.username, u.fullname
            FROM {$this->table} c
            LEFT JOIN posts p ON c.post_id = p.id
            LEFT JOIN users u ON c.user_id = u.id
            ORDER BY c.created_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([(int)$perPage, (int)$offset]);

        return $stmt->fetchAll();
    }

    /**
     * Xóa bình luận và các replies của nó
     */
    public function deleteWithReplies($commentId)
    {
        // Xóa các replies trước
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE parent_id = ?");
        $stmt->execute([$commentId]);

        // Xóa bình luận chính
        return $this->delete($commentId);
    }

    /**
     * Kiểm tra comment thuộc về user
     */
    public function belongsToUser($commentId, $userId)
    {
        $stmt = $this->db->prepare("SELECT user_id FROM {$this->table} WHERE id = ?");
        $stmt->execute([$commentId]);
        $comment = $stmt->fetch();

        return $comment && $comment['user_id'] == $userId;
    }

    /**
     * Lấy post_id từ comment_id
     */
    public function getPostId($commentId)
    {
        $stmt = $this->db->prepare("SELECT post_id FROM {$this->table} WHERE id = ?");
        $stmt->execute([$commentId]);
        $comment = $stmt->fetch();

        return $comment ? $comment['post_id'] : null;
    }
}
