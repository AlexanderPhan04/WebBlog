<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Kết quả tìm kiếm: "<?php echo htmlspecialchars($keyword); ?>"</h1>
        <p class="text-muted">Tìm thấy <?php echo count($posts); ?> kết quả</p>
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
                        </p>
                        <p class="card-text"><?php echo htmlspecialchars($post['excerpt']); ?></p>
                        <a href="<?php echo BASE_URL; ?>/post/show/<?php echo $post['slug']; ?>" class="btn btn-primary btn-sm">
                            Đọc tiếp <i class="bi bi-arrow-right"></i>
                        </a>
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
                                <a class="page-link" href="<?php echo BASE_URL; ?>/home/search?q=<?php echo urlencode($keyword); ?>&page=<?php echo $currentPage - 1; ?>">Trước</a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?php echo $i === $currentPage ? 'active' : ''; ?>">
                                <a class="page-link" href="<?php echo BASE_URL; ?>/home/search?q=<?php echo urlencode($keyword); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($currentPage < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo BASE_URL; ?>/home/search?q=<?php echo urlencode($keyword); ?>&page=<?php echo $currentPage + 1; ?>">Sau</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="alert alert-info">Không tìm thấy bài viết nào phù hợp.</div>
            <a href="<?php echo BASE_URL; ?>" class="btn btn-primary">Quay lại trang chủ</a>
        </div>
    <?php endif; ?>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>