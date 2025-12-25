<?php

/**
 * Controller Auth - Xử lý đăng ký, đăng nhập, đăng xuất
 */
class AuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = $this->model('User');
    }

    /**
     * Trang đăng ký
     */
    public function register()
    {
        if ($this->isLoggedIn()) {
            $this->redirect('');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $fullname = trim($_POST['fullname'] ?? '');

            // Validate
            $errors = [];

            if (empty($username) || strlen($username) < 3) {
                $errors[] = 'Username phải có ít nhất 3 ký tự';
            }

            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email không hợp lệ';
            }

            if (empty($password) || strlen($password) < PASSWORD_MIN_LENGTH) {
                $errors[] = 'Mật khẩu phải có ít nhất ' . PASSWORD_MIN_LENGTH . ' ký tự';
            }

            if ($password !== $confirmPassword) {
                $errors[] = 'Mật khẩu xác nhận không khớp';
            }

            if (empty($fullname)) {
                $errors[] = 'Vui lòng nhập họ tên';
            }

            if (empty($errors)) {
                $result = $this->userModel->register($username, $email, $password, $fullname);

                if ($result['success']) {
                    $this->setFlash('success', $result['message']);
                    $this->redirect('auth/login');
                } else {
                    $errors[] = $result['message'];
                }
            }

            $this->view('auth/register', [
                'errors' => $errors,
                'username' => $username,
                'email' => $email,
                'fullname' => $fullname,
                'title' => 'Đăng ký'
            ]);
        } else {
            $this->view('auth/register', ['title' => 'Đăng ký']);
        }
    }

    /**
     * Trang đăng nhập
     */
    public function login()
    {
        if ($this->isLoggedIn()) {
            $this->redirect('');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            $errors = [];

            if (empty($username)) {
                $errors[] = 'Vui lòng nhập username';
            }

            if (empty($password)) {
                $errors[] = 'Vui lòng nhập mật khẩu';
            }

            if (empty($errors)) {
                $result = $this->userModel->login($username, $password);

                if ($result['success']) {
                    // Lưu thông tin vào session
                    $_SESSION['user_id'] = $result['user']['id'];
                    $_SESSION['username'] = $result['user']['username'];
                    $_SESSION['fullname'] = $result['user']['fullname'];
                    $_SESSION['role'] = $result['user']['role'];

                    $this->setFlash('success', 'Đăng nhập thành công!');
                    $this->redirect('');
                } else {
                    $errors[] = $result['message'];
                }
            }

            $this->view('auth/login', [
                'errors' => $errors,
                'username' => $username,
                'title' => 'Đăng nhập'
            ]);
        } else {
            $this->view('auth/login', ['title' => 'Đăng nhập']);
        }
    }

    /**
     * Đăng xuất
     */
    public function logout()
    {
        session_destroy();
        $this->redirect('');
    }
}
