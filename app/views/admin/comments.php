<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="row">
    <div class="col-12">
        <h1 class="mb-4"><i class="bi bi-chat-dots"></i> Quản lý bình luận</h1>
        <a href="<?php echo BASE_URL; ?>/admin" class="btn btn-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Quay lại Dashboard
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Bài viết</th>
                                <th>Người bình luận</th>
                                <th>Nội dung</th>
                                <th>Ngày đăng</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($comments as $comment): ?>
                                <tr>
                                    <td><?php echo $comment['id']; ?></td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>/post/show/<?php echo $comment['post_slug']; ?>#comment-<?php echo $comment['id']; ?>" target="_blank">
                                            <?php echo htmlspecialchars($comment['post_title']); ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php
                                        if ($comment['user_id']) {
                                            echo htmlspecialchars($comment['fullname']);
                                        } else {
                                            echo htmlspecialchars($comment['name']) . ' (Khách)';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $content = htmlspecialchars($comment['content']);
                                        echo mb_strlen($content) > 100 ? mb_substr($content, 0, 100) . '...' : $content;
                                        ?>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($comment['created_at'])); ?></td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>/admin/deleteComment/<?php echo $comment['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa bình luận này?')">
                                            <i class="bi bi-trash"></i> Xóa
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Phân trang -->
                <?php if ($totalPages > 1): ?>
                    <nav class="mt-3">
                        <ul class="pagination justify-content-center">
                            <?php if ($currentPage > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo BASE_URL; ?>/admin/comments?page=<?php echo $currentPage - 1; ?>">Trước</a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo $i === $currentPage ? 'active' : ''; ?>">
                                    <a class="page-link" href="<?php echo BASE_URL; ?>/admin/comments?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($currentPage < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo BASE_URL; ?>/admin/comments?page=<?php echo $currentPage + 1; ?>">Sau</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>