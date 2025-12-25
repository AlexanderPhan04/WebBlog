<?php

/**
 * Controller Home - Trang chủ và các trang chung
 */
class HomeController extends Controller
{
    private $postModel;

    public function __construct()
    {
        $this->postModel = $this->model('Post');
    }

    /**
     * Trang chủ - Hiển thị danh sách bài viết
     */
    public function index()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page);

        $posts = $this->postModel->getPostsPaginated($page);
        $totalPosts = $this->postModel->count();
        $totalPages = ceil($totalPosts / POSTS_PER_PAGE);

        $this->view('home/index', [
            'posts' => $posts,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'title' => 'Trang chủ'
        ]);
    }

    /**
     * Tìm kiếm bài viết
     */
    public function search()
    {
        $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page);

        if (empty($keyword)) {
            $this->redirect('');
        }

        $posts = $this->postModel->search($keyword, $page);
        $totalPosts = $this->postModel->countSearch($keyword);
        $totalPages = ceil($totalPosts / POSTS_PER_PAGE);

        $this->view('home/search', [
            'posts' => $posts,
            'keyword' => $keyword,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'title' => 'Tìm kiếm: ' . $keyword
        ]);
    }
}
