<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <article class="card">
            <div class="card-body">
                <h1 class="card-title"><?php echo htmlspecialchars($post['title']); ?></h1>

                <div class="text-muted mb-3">
                    <i class="bi bi-person"></i> <?php echo htmlspecialchars($post['fullname']); ?>
                    &nbsp;|&nbsp;
                    <i class="bi bi-calendar"></i> <?php echo date('d/m/Y H:i', strtotime($post['created_at'])); ?>
                    &nbsp;|&nbsp;
                    <i class="bi bi-eye"></i> <?php echo $post['views']; ?> lượt xem
                    &nbsp;|&nbsp;
                    <i class="bi bi-chat"></i> <?php echo count($comments); ?> bình luận
                    <?php if ($post['category_name']): ?>
                        &nbsp;|&nbsp;
                        <i class="bi bi-folder"></i> <?php echo htmlspecialchars($post['category_name']); ?>
                    <?php endif; ?>
                </div>

                <?php if ($post['tags']): ?>
                    <div class="mb-3">
                        <?php
                        $tags = explode(',', $post['tags']);
                        foreach ($tags as $tag):
                            $tag = trim($tag);
                            if ($tag):
                        ?>
                                <span class="badge bg-secondary"><?php echo htmlspecialchars($tag); ?></span>
                        <?php
                            endif;
                        endforeach;
                        ?>
                    </div>
                <?php endif; ?>

                <hr>

                <div class="post-content">
                    <?php echo $post['content']; ?>
                </div>

                <hr>

                <div class="d-flex justify-content-between align-items-center">
                    <a href="<?php echo BASE_URL; ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Quay lại
                    </a>

                    <?php if (isset($_SESSION['user_id']) && ($_SESSION['user_id'] == $post['user_id'] || (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'))): ?>
                        <div>
                            <a href="<?php echo BASE_URL; ?>/post/edit/<?php echo $post['id']; ?>" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Sửa
                            </a>
                            <a href="<?php echo BASE_URL; ?>/post/delete/<?php echo $post['id']; ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn xóa bài viết này?')">
                                <i class="bi bi-trash"></i> Xóa
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </article>

        <!-- Comments Section -->
        <div class="card mt-4">
            <div class="card-header bg-light">
                <h4 class="mb-0"><i class="bi bi-chat-dots"></i> Bình luận (<?php echo count($comments); ?>)</h4>
            </div>
            <div class="card-body">
                <!-- Comment Form -->
                <form method="POST" action="<?php echo BASE_URL; ?>/post/addComment" class="mb-4">
                    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                    <input type="hidden" name="parent_id" value="0" id="parent_id">

                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" placeholder="Tên của bạn *" required>
                            </div>
                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" placeholder="Email *" required>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <textarea class="form-control" name="content" rows="3" placeholder="Nhập bình luận..." required></textarea>
                    </div>

                    <div id="reply-info" class="alert alert-info d-none mb-3">
                        Đang trả lời bình luận... <button type="button" class="btn-close float-end" onclick="cancelReply()"></button>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send"></i> Gửi bình luận
                    </button>
                </form>

                <!-- Comments List -->
                <div id="comments-list">
                    <?php
                    function displayComments($comments, $level = 0)
                    {
                        foreach ($comments as $comment):
                            $marginLeft = $level * 30;
                    ?>
                            <div class="comment-item mb-3 p-3 border rounded" style="margin-left: <?php echo $marginLeft; ?>px;" id="comment-<?php echo $comment['id']; ?>">
                                <div class="d-flex justify-content-between">
                                    <strong>
                                        <?php
                                        if ($comment['user_id']) {
                                            echo htmlspecialchars($comment['fullname']);
                                        } else {
                                            echo htmlspecialchars($comment['name']);
                                        }
                                        ?>
                                    </strong>
                                    <small class="text-muted"><?php echo date('d/m/Y H:i', strtotime($comment['created_at'])); ?></small>
                                </div>
                                <p class="mb-2 mt-2"><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
                                <div>
                                    <?php if ($level < 2): // Giới hạn 3 cấp (0, 1, 2) 
                                    ?>
                                        <button class="btn btn-sm btn-outline-primary" onclick="setReply(<?php echo $comment['id']; ?>, '<?php echo $comment['user_id'] ? htmlspecialchars($comment['fullname']) : htmlspecialchars($comment['name']); ?>')">
                                            <i class="bi bi-reply"></i> Trả lời
                                        </button>
                                    <?php endif; ?>

                                    <?php
                                    // Có thể xóa nếu: admin, tác giả bài viết, hoặc người comment
                                    $canDelete = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') ||
                                        (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $comment['user_id']) ||
                                        (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $GLOBALS['post']['user_id']);

                                    if ($canDelete):
                                    ?>
                                        <a href="<?php echo BASE_URL; ?>/post/deleteComment/<?php echo $comment['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc muốn xóa bình luận này?')">
                                            <i class="bi bi-trash"></i> Xóa
                                        </a>
                                    <?php endif; ?>
                                </div>

                                <?php if (!empty($comment['replies'])): ?>
                                    <?php displayComments($comment['replies'], $level + 1); ?>
                                <?php endif; ?>
                            </div>
                    <?php
                        endforeach;
                    }

                    // Lưu post vào global để dùng trong function
                    $GLOBALS['post'] = $post;

                    if (!empty($comments)) {
                        displayComments($comments);
                    } else {
                        echo '<p class="text-muted">Chưa có bình luận nào. Hãy là người đầu tiên bình luận!</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function setReply(commentId, commenterName) {
        document.getElementById('parent_id').value = commentId;
        document.getElementById('reply-info').classList.remove('d-none');
        document.getElementById('reply-info').innerHTML = 'Đang trả lời <strong>' + commenterName + '</strong>... <button type="button" class="btn-close float-end" onclick="cancelReply()"></button>';
        document.querySelector('textarea[name="content"]').focus();
    }

    function cancelReply() {
        document.getElementById('parent_id').value = '0';
        document.getElementById('reply-info').classList.add('d-none');
    }
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>