<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0"><i class="bi bi-pencil-square"></i> Tạo bài viết mới</h3>
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

                <form method="POST" action="<?php echo BASE_URL; ?>/post/create">
                    <div class="mb-3">
                        <label for="title" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($title ?? ''); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Danh mục</label>
                        <select class="form-select" id="category_id" name="category_id">
                            <option value="">-- Chọn danh mục --</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>" <?php echo (isset($categoryId) && $categoryId == $category['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="tags" class="form-label">Tags (phân cách bằng dấu phẩy)</label>
                        <input type="text" class="form-control" id="tags" name="tags" value="<?php echo htmlspecialchars($tags ?? ''); ?>" placeholder="php, mysql, web">
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Nội dung <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="content" name="content" rows="15" required><?php echo htmlspecialchars($content ?? ''); ?></textarea>
                        <small class="text-muted">
                            Hỗ trợ HTML: &lt;p&gt;, &lt;strong&gt;, &lt;em&gt;, &lt;ul&gt;, &lt;ol&gt;, &lt;li&gt;, &lt;a&gt;, &lt;img&gt;, &lt;h1&gt;-&lt;h4&gt;, &lt;blockquote&gt;
                        </small>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?php echo BASE_URL; ?>" class="btn btn-secondary">Hủy</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Đăng bài
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>