<?php

/**
 * Controller Admin - Quản trị hệ thống
 */
class AdminController extends Controller
{
    private $userModel;
    private $postModel;
    private $commentModel;

    public function __construct()
    {
        $this->userModel = $this->model('User');
        $this->postModel = $this->model('Post');
        $this->commentModel = $this->model('Comment');
    }

    /**
     * Trang chủ admin - Dashboard
     */
    public function index()
    {
        $this->requireAdmin();

        $totalUsers = $this->userModel->getTotalUsers();
        $totalPosts = $this->postModel->count();
        $totalComments = $this->commentModel->count();

        $this->view('admin/dashboard', [
            'totalUsers' => $totalUsers,
            'totalPosts' => $totalPosts,
            'totalComments' => $totalComments,
            'title' => 'Quản trị - Dashboard'
        ]);
    }

    /**
     * Quản lý người dùng
     */
    public function users()
    {
        $this->requireAdmin();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page);

        $users = $this->userModel->getUsersPaginated($page);
        $totalUsers = $this->userModel->getTotalUsers();
        $totalPages = ceil($totalUsers / 20);

        $this->view('admin/users', [
            'users' => $users,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'title' => 'Quản lý người dùng'
        ]);
    }

    /**
     * Thay đổi role user
     */
    public function changeRole()
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = (int)($_POST['user_id'] ?? 0);
            $role = $_POST['role'] ?? '';

            if (in_array($role, ['user', 'admin'])) {
                $this->userModel->updateRole($userId, $role);
                $this->setFlash('success', 'Cập nhật role thành công!');
            }
        }

        $this->redirect('admin/users');
    }

    /**
     * Xóa user
     */
    public function deleteUser($id)
    {
        $this->requireAdmin();

        // Không cho xóa chính mình
        if ($id == $_SESSION['user_id']) {
            $this->setFlash('error', 'Không thể xóa tài khoản của chính bạn');
            $this->redirect('admin/users');
        }

        if ($this->userModel->delete($id)) {
            $this->setFlash('success', 'Xóa người dùng thành công!');
        } else {
            $this->setFlash('error', 'Có lỗi xảy ra');
        }

        $this->redirect('admin/users');
    }

    /**
     * Quản lý bài viết
     */
    public function posts()
    {
        $this->requireAdmin();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page);

        $posts = $this->postModel->getPostsPaginated($page);
        $totalPosts = $this->postModel->count();
        $totalPages = ceil($totalPosts / POSTS_PER_PAGE);

        $this->view('admin/posts', [
            'posts' => $posts,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'title' => 'Quản lý bài viết'
        ]);
    }

    /**
     * Quản lý bình luận
     */
    public function comments()
    {
        $this->requireAdmin();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page);

        $comments = $this->commentModel->getAllComments($page);
        $totalComments = $this->commentModel->count();
        $totalPages = ceil($totalComments / COMMENTS_PER_PAGE);

        $this->view('admin/comments', [
            'comments' => $comments,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'title' => 'Quản lý bình luận'
        ]);
    }

    /**
     * Xóa bình luận từ admin
     */
    public function deleteComment($id)
    {
        $this->requireAdmin();

        if ($this->commentModel->deleteWithReplies($id)) {
            $this->setFlash('success', 'Xóa bình luận thành công!');
        } else {
            $this->setFlash('error', 'Có lỗi xảy ra');
        }

        $this->redirect('admin/comments');
    }
}
