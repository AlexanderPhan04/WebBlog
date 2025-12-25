<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="row">
    <div class="col-12">
        <h1 class="mb-4"><i class="bi bi-file-text"></i> Quản lý bài viết</h1>
        <div class="mb-3">
            <a href="<?php echo BASE_URL; ?>/admin" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại Dashboard
            </a>
            <a href="<?php echo BASE_URL; ?>/post/create" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Tạo bài viết mới
            </a>
        </div>
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
                                <th>Tiêu đề</th>
                                <th>Tác giả</th>
                                <th>Danh mục</th>
                                <th>Lượt xem</th>
                                <th>Ngày đăng</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($posts as $post): ?>
                                <tr>
                                    <td><?php echo $post['id']; ?></td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>/post/show/<?php echo $post['slug']; ?>" target="_blank">
                                            <?php echo htmlspecialchars($post['title']); ?>
                                        </a>
                                    </td>
                                    <td><?php echo htmlspecialchars($post['fullname']); ?></td>
                                    <td><?php echo htmlspecialchars($post['category_name'] ?? 'Chưa phân loại'); ?></td>
                                    <td><?php echo $post['views']; ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($post['created_at'])); ?></td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>/post/edit/<?php echo $post['id']; ?>" class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?php echo BASE_URL; ?>/post/delete/<?php echo $post['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa bài viết này?')">
                                            <i class="bi bi-trash"></i>
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
                                    <a class="page-link" href="<?php echo BASE_URL; ?>/admin/posts?page=<?php echo $currentPage - 1; ?>">Trước</a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo $i === $currentPage ? 'active' : ''; ?>">
                                    <a class="page-link" href="<?php echo BASE_URL; ?>/admin/posts?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($currentPage < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo BASE_URL; ?>/admin/posts?page=<?php echo $currentPage + 1; ?>">Sau</a>
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