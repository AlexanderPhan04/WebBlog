<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="row">
    <div class="col-12">
        <h1 class="mb-4"><i class="bi bi-speedometer2"></i> Quản trị - Dashboard</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Người dùng</h5>
                        <h2 class="mb-0"><?php echo $totalUsers; ?></h2>
                    </div>
                    <div>
                        <i class="bi bi-people" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="<?php echo BASE_URL; ?>/admin/users" class="text-white text-decoration-none">
                    Xem chi tiết <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Bài viết</h5>
                        <h2 class="mb-0"><?php echo $totalPosts; ?></h2>
                    </div>
                    <div>
                        <i class="bi bi-file-text" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="<?php echo BASE_URL; ?>/admin/posts" class="text-white text-decoration-none">
                    Xem chi tiết <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Bình luận</h5>
                        <h2 class="mb-0"><?php echo $totalComments; ?></h2>
                    </div>
                    <div>
                        <i class="bi bi-chat-dots" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="<?php echo BASE_URL; ?>/admin/comments" class="text-white text-decoration-none">
                    Xem chi tiết <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Quản lý nhanh</h4>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <a href="<?php echo BASE_URL; ?>/admin/users" class="list-group-item list-group-item-action">
                        <i class="bi bi-people"></i> Quản lý người dùng
                    </a>
                    <a href="<?php echo BASE_URL; ?>/admin/posts" class="list-group-item list-group-item-action">
                        <i class="bi bi-file-text"></i> Quản lý bài viết
                    </a>
                    <a href="<?php echo BASE_URL; ?>/admin/comments" class="list-group-item list-group-item-action">
                        <i class="bi bi-chat-dots"></i> Quản lý bình luận
                    </a>
                    <a href="<?php echo BASE_URL; ?>" class="list-group-item list-group-item-action">
                        <i class="bi bi-house"></i> Quay lại trang chủ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>