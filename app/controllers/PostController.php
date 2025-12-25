<?php

/**
 * Controller Post - Xử lý bài viết
 */
class PostController extends Controller
{
    private $postModel;
    private $commentModel;
    private $categoryModel;

    public function __construct()
    {
        $this->postModel = $this->model('Post');
        $this->commentModel = $this->model('Comment');
        $this->categoryModel = $this->model('Category');
    }

    /**
     * Xem chi tiết bài viết
     */
    public function show($slug)
    {
        $post = $this->postModel->getBySlug($slug);

        if (!$post) {
            die('Bài viết không tồn tại');
        }

        // Tăng lượt xem
        $this->postModel->incrementViews($post['id']);
        $post['views']++;

        // Lấy comments
        $comments = $this->commentModel->getCommentsByPost($post['id']);

        $this->view('posts/view', [
            'post' => $post,
            'comments' => $comments,
            'title' => $post['title']
        ]);
    }

    /**
     * Tạo bài viết mới
     */
    public function create()
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $categoryId = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
            $tags = trim($_POST['tags'] ?? '');

            $errors = [];

            if (empty($title)) {
                $errors[] = 'Vui lòng nhập tiêu đề';
            }

            if (empty($content)) {
                $errors[] = 'Vui lòng nhập nội dung';
            }

            if (empty($errors)) {
                $result = $this->postModel->createPost(
                    $title,
                    $content,
                    $_SESSION['user_id'],
                    $categoryId,
                    $tags
                );

                if ($result) {
                    $this->setFlash('success', 'Tạo bài viết thành công!');
                    $this->redirect('');
                } else {
                    $errors[] = 'Có lỗi xảy ra, vui lòng thử lại';
                }
            }

            $categories = $this->categoryModel->getAll();

            $this->view('posts/create', [
                'errors' => $errors,
                'title' => $title,
                'content' => $content,
                'categoryId' => $categoryId,
                'tags' => $tags,
                'categories' => $categories,
                'pageTitle' => 'Tạo bài viết mới'
            ]);
        } else {
            $categories = $this->categoryModel->getAll();

            $this->view('posts/create', [
                'categories' => $categories,
                'title' => 'Tạo bài viết mới'
            ]);
        }
    }

    /**
     * Chỉnh sửa bài viết
     */
    public function edit($id)
    {
        $this->requireLogin();

        $post = $this->postModel->getById($id);

        if (!$post) {
            die('Bài viết không tồn tại');
        }

        // Kiểm tra quyền
        if (!$this->isAdmin() && !$this->postModel->isAuthor($id, $_SESSION['user_id'])) {
            $this->setFlash('error', 'Bạn không có quyền chỉnh sửa bài viết này');
            $this->redirect('');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $categoryId = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
            $tags = trim($_POST['tags'] ?? '');

            $errors = [];

            if (empty($title)) {
                $errors[] = 'Vui lòng nhập tiêu đề';
            }

            if (empty($content)) {
                $errors[] = 'Vui lòng nhập nội dung';
            }

            if (empty($errors)) {
                $result = $this->postModel->updatePost($id, $title, $content, $categoryId, $tags);

                if ($result) {
                    $this->setFlash('success', 'Cập nhật bài viết thành công!');
                    $this->redirect('post/show/' . $post['slug']);
                } else {
                    $errors[] = 'Có lỗi xảy ra, vui lòng thử lại';
                }
            }

            $categories = $this->categoryModel->getAll();

            $this->view('posts/edit', [
                'errors' => $errors,
                'post' => $post,
                'categories' => $categories,
                'title' => 'Chỉnh sửa bài viết'
            ]);
        } else {
            $categories = $this->categoryModel->getAll();

            $this->view('posts/edit', [
                'post' => $post,
                'categories' => $categories,
                'title' => 'Chỉnh sửa bài viết'
            ]);
        }
    }

    /**
     * Xóa bài viết
     */
    public function delete($id)
    {
        $this->requireLogin();

        $post = $this->postModel->getById($id);

        if (!$post) {
            die('Bài viết không tồn tại');
        }

        // Kiểm tra quyền
        if (!$this->isAdmin() && !$this->postModel->isAuthor($id, $_SESSION['user_id'])) {
            $this->setFlash('error', 'Bạn không có quyền xóa bài viết này');
            $this->redirect('');
        }

        if ($this->postModel->delete($id)) {
            $this->setFlash('success', 'Xóa bài viết thành công!');
        } else {
            $this->setFlash('error', 'Có lỗi xảy ra');
        }

        $this->redirect('');
    }

    /**
     * Thêm bình luận
     */
    public function addComment()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('');
        }

        $postId = (int)($_POST['post_id'] ?? 0);
        $content = trim($_POST['content'] ?? '');
        $parentId = (int)($_POST['parent_id'] ?? 0);

        $errors = [];

        if (empty($content)) {
            $errors[] = 'Vui lòng nhập nội dung bình luận';
        }

        // Nếu là guest, yêu cầu name và email
        if (!$this->isLoggedIn()) {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');

            if (empty($name)) {
                $errors[] = 'Vui lòng nhập tên';
            }

            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email không hợp lệ';
            }

            if (empty($errors)) {
                $this->commentModel->createComment($postId, $content, null, $name, $email, $parentId);
            }
        } else {
            if (empty($errors)) {
                $this->commentModel->createComment($postId, $content, $_SESSION['user_id'], null, null, $parentId);
            }
        }

        // Lấy slug của post để redirect
        $post = $this->postModel->getById($postId);

        if ($post) {
            if (empty($errors)) {
                $this->setFlash('success', 'Bình luận thành công!');
            } else {
                $this->setFlash('error', implode('<br>', $errors));
            }
            $this->redirect('post/show/' . $post['slug']);
        }

        $this->redirect('');
    }

    /**
     * Xóa bình luận
     */
    public function deleteComment($id)
    {
        $this->requireLogin();

        $comment = $this->commentModel->getById($id);

        if (!$comment) {
            die('Bình luận không tồn tại');
        }

        // Lấy thông tin post
        $post = $this->postModel->getById($comment['post_id']);

        // Kiểm tra quyền: admin, tác giả bài viết, hoặc người comment
        $canDelete = $this->isAdmin() ||
            $this->postModel->isAuthor($comment['post_id'], $_SESSION['user_id']) ||
            $this->commentModel->belongsToUser($id, $_SESSION['user_id']);

        if (!$canDelete) {
            $this->setFlash('error', 'Bạn không có quyền xóa bình luận này');
            $this->redirect('post/show/' . $post['slug']);
        }

        if ($this->commentModel->deleteWithReplies($id)) {
            $this->setFlash('success', 'Xóa bình luận thành công!');
        } else {
            $this->setFlash('error', 'Có lỗi xảy ra');
        }

        $this->redirect('post/show/' . $post['slug']);
    }
}
