<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0"><i class="bi bi-box-arrow-in-right"></i> Đăng nhập</h3>
            </div>
            <div class="card-body">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo BASE_URL; ?>/auth/login">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($username ?? ''); ?>" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Đăng nhập</button>
                    </div>
                </form>

                <hr>
                <p class="text-center mb-2">
                    Chưa có tài khoản? <a href="<?php echo BASE_URL; ?>/auth/register">Đăng ký ngay</a>
                </p>
                <p class="text-center text-muted mb-0">
                    <small>Quên mật khẩu? Vui lòng liên hệ admin</small>
                </p>
            </div>
        </div>

        <div class="alert alert-info mt-3">
            <strong>Tài khoản demo:</strong><br>
            <small>
                Admin: <code>admin</code> / <code>password</code><br>
                User: <code>nguyenvana</code> / <code>password</code>
            </small>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>