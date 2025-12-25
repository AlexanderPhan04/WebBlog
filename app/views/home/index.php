<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Bài viết mới nhất</h1>
    </div>
</div>

<div class="row">
    <?php if (!empty($posts)): ?>
        <?php foreach ($posts as $post): ?>
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title">
                            <a href="<?php echo BASE_URL; ?>/post/view/<?php echo $post['slug']; ?>" class="text-decoration-none">
                                <?php echo htmlspecialchars($post['title']); ?>
                            </a>
                        </h2>
                        <p class="text-muted small">
                            <i class="bi bi-person"></i> <?php echo htmlspecialchars($post['fullname']); ?>
                            &nbsp;|&nbsp;
                            <i class="bi bi-calendar"></i> <?php echo date('d/m/Y H:i', strtotime($post['created_at'])); ?>
                            &nbsp;|&nbsp;
                            <i class="bi bi-eye"></i> <?php echo $post['views']; ?> lượt xem
                            &nbsp;|&nbsp;
                            <i class="bi bi-chat"></i> <?php echo $post['comment_count']; ?> bình luận
                            <?php if ($post['category_name']): ?>
                                &nbsp;|&nbsp;
                                <i class="bi bi-folder"></i> <?php echo htmlspecialchars($post['category_name']); ?>
                            <?php endif; ?>
                        </p>
                        <p class="card-text"><?php echo htmlspecialchars($post['excerpt']); ?></p>
                        <a href="<?php echo BASE_URL; ?>/post/show/<?php echo $post['slug']; ?>" class="btn btn-primary btn-sm">
                            Đọc tiếp <i class="bi bi-arrow-right"></i>
                        </a>

                        <?php if (isset($_SESSION['user_id']) && ($_SESSION['user_id'] == $post['user_id'] || (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'))): ?>
                            <a href="<?php echo BASE_URL; ?>/post/edit/<?php echo $post['id']; ?>" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i> Sửa
                            </a>
                            <a href="<?php echo BASE_URL; ?>/post/delete/<?php echo $post['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa bài viết này?')">
                                <i class="bi bi-trash"></i> Xóa
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Phân trang -->
        <?php if ($totalPages > 1): ?>
            <div class="col-12">
                <nav>
                    <ul class="pagination justify-content-center">
                        <?php if ($currentPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo BASE_URL; ?>?page=<?php echo $currentPage - 1; ?>">Trước</a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?php echo $i === $currentPage ? 'active' : ''; ?>">
                                <a class="page-link" href="<?php echo BASE_URL; ?>?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($currentPage < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo BASE_URL; ?>?page=<?php echo $currentPage + 1; ?>">Sau</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="alert alert-info">Chưa có bài viết nào.</div>
        </div>
    <?php endif; ?>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>