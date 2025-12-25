-- Tạo database
CREATE DATABASE IF NOT EXISTS webblog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE webblog;

-- Bảng users
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    fullname VARCHAR(100) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng categories
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng posts
CREATE TABLE posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content TEXT NOT NULL,
    excerpt VARCHAR(500),
    user_id INT NOT NULL,
    category_id INT,
    tags VARCHAR(255),
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_slug (slug),
    INDEX idx_user_id (user_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng comments (hỗ trợ bình luận phân cấp)
CREATE TABLE comments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    post_id INT NOT NULL,
    parent_id INT DEFAULT 0,
    name VARCHAR(100),
    email VARCHAR(100),
    content TEXT NOT NULL,
    user_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_post_id (post_id),
    INDEX idx_parent_id (parent_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Thêm dữ liệu mẫu

-- Thêm users (5 users, 1 admin)
INSERT INTO users (username, password, email, fullname, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@blog.com', 'Administrator', 'admin'),
('nguyenvana', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'nguyenvana@email.com', 'Nguyễn Văn A', 'user'),
('tranthib', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'tranthib@email.com', 'Trần Thị B', 'user'),
('phamvanc', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'phamvanc@email.com', 'Phạm Văn C', 'user'),
('lehoangd', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'lehoangd@email.com', 'Lê Hoàng D', 'user');

-- Thêm categories
INSERT INTO categories (name, slug) VALUES
('Công nghệ', 'cong-nghe'),
('Lập trình', 'lap-trinh'),
('Thiết kế', 'thiet-ke'),
('Kinh doanh', 'kinh-doanh'),
('Đời sống', 'doi-song');

-- Thêm 10 bài viết mẫu
INSERT INTO posts (title, slug, content, excerpt, user_id, category_id, tags, views) VALUES
('Giới thiệu về PHP và MySQL', 'gioi-thieu-ve-php-va-mysql', 
'<p>PHP là một ngôn ngữ lập trình kịch bản phía máy chủ được thiết kế đặc biệt cho phát triển web. MySQL là hệ quản trị cơ sở dữ liệu quan hệ mã nguồn mở phổ biến nhất.</p><p>PHP và MySQL là combo hoàn hảo để xây dựng các ứng dụng web động. PHP xử lý logic phía server, trong khi MySQL lưu trữ và quản lý dữ liệu.</p><h2>Tại sao chọn PHP?</h2><ul><li>Dễ học và sử dụng</li><li>Hỗ trợ đa nền tảng</li><li>Cộng đồng lớn</li><li>Miễn phí và mã nguồn mở</li></ul>', 
'PHP là ngôn ngữ lập trình kịch bản phía server được thiết kế đặc biệt cho phát triển web. Cùng tìm hiểu về PHP và MySQL.',
1, 2, 'php,mysql,web', 150),

('Xây dựng ứng dụng CRUD với PHP', 'xay-dung-ung-dung-crud-voi-php',
'<p>CRUD là viết tắt của Create, Read, Update, Delete - bốn thao tác cơ bản với cơ sở dữ liệu. Trong bài viết này, chúng ta sẽ xây dựng một ứng dụng CRUD đơn giản với PHP và MySQL.</p><h2>Các bước thực hiện</h2><ol><li>Thiết kế database</li><li>Tạo form nhập liệu</li><li>Xử lý thêm mới dữ liệu</li><li>Hiển thị danh sách</li><li>Cập nhật và xóa</li></ol><p>Với các bước trên, bạn có thể xây dựng được một ứng dụng quản lý cơ bản.</p>',
'Hướng dẫn xây dựng ứng dụng CRUD (Create, Read, Update, Delete) đơn giản với PHP và MySQL.',
2, 2, 'php,crud,tutorial', 230),

('Bảo mật ứng dụng web PHP', 'bao-mat-ung-dung-web-php',
'<p>Bảo mật là vấn đề quan trọng nhất khi phát triển ứng dụng web. Dưới đây là các biện pháp bảo mật cơ bản cho ứng dụng PHP.</p><h2>SQL Injection</h2><p>Sử dụng Prepared Statements với PDO để chống SQL Injection.</p><h2>XSS Attack</h2><p>Sử dụng htmlspecialchars() để escape output và ngăn chặn XSS.</p><h2>CSRF Protection</h2><p>Sử dụng token để bảo vệ form khỏi CSRF attack.</p><h2>Password Hashing</h2><p>Luôn sử dụng password_hash() và password_verify() để mã hóa mật khẩu.</p>',
'Tìm hiểu các biện pháp bảo mật cơ bản cho ứng dụng web PHP: SQL Injection, XSS, CSRF, Password Hashing.',
1, 2, 'php,security,bảo mật', 340),

('Thiết kế responsive với Bootstrap 5', 'thiet-ke-responsive-voi-bootstrap-5',
'<p>Bootstrap 5 là framework CSS phổ biến nhất giúp bạn xây dựng website responsive nhanh chóng. Trong phiên bản 5, Bootstrap đã loại bỏ jQuery và sử dụng vanilla JavaScript.</p><h2>Grid System</h2><p>Bootstrap sử dụng hệ thống lưới 12 cột linh hoạt, responsive và mobile-first.</p><h2>Components</h2><p>Bootstrap cung cấp nhiều components có sẵn: buttons, cards, modals, navbars...</p><p>Với Bootstrap, bạn có thể tạo ra giao diện đẹp mà không cần viết nhiều CSS.</p>',
'Hướng dẫn sử dụng Bootstrap 5 để thiết kế giao diện responsive. Grid system, components và utilities.',
3, 3, 'bootstrap,css,responsive', 189),

('Kiến trúc MVC trong PHP', 'kien-truc-mvc-trong-php',
'<p>MVC (Model-View-Controller) là một mẫu kiến trúc phần mềm phổ biến trong phát triển web. MVC chia ứng dụng thành 3 phần chính:</p><h2>Model</h2><p>Quản lý dữ liệu và logic nghiệp vụ. Tương tác với database.</p><h2>View</h2><p>Hiển thị dữ liệu cho người dùng. Chứa HTML, CSS, JavaScript.</p><h2>Controller</h2><p>Xử lý request từ user, gọi Model lấy dữ liệu, truyền cho View hiển thị.</p><p>MVC giúp code dễ bảo trì, mở rộng và tái sử dụng.</p>',
'Tìm hiểu về kiến trúc MVC (Model-View-Controller) và cách áp dụng vào ứng dụng PHP.',
2, 2, 'mvc,php,architecture', 276),

('JavaScript ES6+ Features', 'javascript-es6-features',
'<p>ES6 (ECMAScript 2015) đã mang đến nhiều tính năng mới cho JavaScript, giúp code ngắn gọn và dễ đọc hơn.</p><h2>Arrow Functions</h2><p>Cú pháp ngắn gọn cho functions: <code>const add = (a, b) => a + b;</code></p><h2>Template Literals</h2><p>String interpolation: <code>`Hello ${name}`</code></p><h2>Destructuring</h2><p>Giải nén array và object một cách dễ dàng.</p><h2>Promises & Async/Await</h2><p>Xử lý bất đồng bộ tốt hơn.</p>',
'Khám phá các tính năng mới của JavaScript ES6+: Arrow Functions, Template Literals, Destructuring, Promises.',
4, 2, 'javascript,es6,frontend', 421),

('Git và GitHub cho người mới bắt đầu', 'git-va-github-cho-nguoi-moi-bat-dau',
'<p>Git là hệ thống quản lý phiên bản phân tán phổ biến nhất hiện nay. GitHub là nền tảng hosting cho Git repositories.</p><h2>Các lệnh Git cơ bản</h2><ul><li>git init - Khởi tạo repository</li><li>git add - Thêm file vào staging</li><li>git commit - Lưu thay đổi</li><li>git push - Đẩy code lên remote</li><li>git pull - Kéo code từ remote</li></ul><p>Sử dụng Git giúp bạn làm việc nhóm hiệu quả và quản lý code tốt hơn.</p>',
'Hướng dẫn sử dụng Git và GitHub cơ bản cho người mới bắt đầu. Các lệnh Git thường dùng.',
5, 1, 'git,github,version control', 312),

('Tối ưu hiệu suất website', 'toi-uu-hieu-suat-website',
'<p>Tốc độ tải trang là yếu tố quan trọng ảnh hưởng đến trải nghiệm người dùng và SEO. Dưới đây là các kỹ thuật tối ưu hiệu suất.</p><h2>Tối ưu hình ảnh</h2><p>Nén ảnh, sử dụng format phù hợp (WebP), lazy loading.</p><h2>Minify CSS/JS</h2><p>Giảm kích thước file CSS và JavaScript.</p><h2>Caching</h2><p>Sử dụng browser caching và server caching.</p><h2>CDN</h2><p>Sử dụng Content Delivery Network để phân phối nội dung nhanh hơn.</p>',
'Các kỹ thuật tối ưu hiệu suất website: Optimize images, minify assets, caching, CDN.',
1, 1, 'performance,optimization,web', 198),

('RESTful API với PHP', 'restful-api-voi-php',
'<p>REST (Representational State Transfer) là một kiến trúc cho việc xây dựng web services. RESTful API sử dụng HTTP methods để thực hiện các thao tác CRUD.</p><h2>HTTP Methods</h2><ul><li>GET - Lấy dữ liệu</li><li>POST - Tạo mới</li><li>PUT - Cập nhật toàn bộ</li><li>PATCH - Cập nhật một phần</li><li>DELETE - Xóa</li></ul><h2>Response Format</h2><p>Thường sử dụng JSON format để trả về dữ liệu.</p><p>RESTful API giúp frontend và backend tách biệt hoàn toàn.</p>',
'Hướng dẫn xây dựng RESTful API với PHP. HTTP methods, JSON response, authentication.',
3, 2, 'api,rest,php,json', 267),

('CSS Grid và Flexbox', 'css-grid-va-flexbox',
'<p>CSS Grid và Flexbox là hai công cụ mạnh mẽ để tạo layout trong CSS hiện đại.</p><h2>Flexbox</h2><p>Flexbox là mô hình layout một chiều, phù hợp cho các components nhỏ và navigation.</p><h2>CSS Grid</h2><p>Grid là mô hình layout hai chiều, phù hợp cho layout tổng thể của trang.</p><h2>Khi nào dùng gì?</h2><p>Sử dụng Flexbox cho layout một chiều (hàng hoặc cột). Sử dụng Grid cho layout phức tạp hai chiều. Bạn có thể kết hợp cả hai!</p>',
'So sánh CSS Grid và Flexbox. Khi nào nên sử dụng Grid, khi nào dùng Flexbox?',
4, 3, 'css,grid,flexbox,layout', 354);

-- Thêm 20+ bình luận mẫu (bao gồm bình luận phân cấp)
INSERT INTO comments (post_id, parent_id, name, email, content, user_id) VALUES
-- Bình luận cho bài 1
(1, 0, NULL, NULL, 'Bài viết rất hữu ích! Cảm ơn bạn đã chia sẻ.', 2),
(1, 1, NULL, NULL, 'Mình cũng đang học PHP, bài viết này giúp mình hiểu rõ hơn.', 3),
(1, 0, 'Khách vãng lai', 'guest1@email.com', 'PHP có khó học không bạn?', NULL),
(1, 3, NULL, NULL, 'PHP khá dễ học cho người mới bắt đầu. Bạn cứ kiên trì là được!', 1),

-- Bình luận cho bài 2
(2, 0, NULL, NULL, 'Code rất dễ hiểu, đã áp dụng thành công!', 4),
(2, 0, NULL, NULL, 'Bạn có thể thêm phần validation cho form không?', 5),
(2, 6, NULL, NULL, 'Mình sẽ viết thêm bài về validation nhé!', 2),

-- Bình luận cho bài 3
(3, 0, NULL, NULL, 'Bảo mật rất quan trọng, cảm ơn đã nhắc nhở!', 3),
(3, 0, 'An Nguyen', 'an.nguyen@email.com', 'Bài viết rất chuyên nghiệp!', NULL),
(3, 8, NULL, NULL, 'Cảm ơn bạn!', 1),

-- Bình luận cho bài 4
(4, 0, NULL, NULL, 'Bootstrap 5 thật sự rất tuyệt! Không cần jQuery nữa.', 2),
(4, 11, NULL, NULL, 'Đúng vậy, Bootstrap 5 đã cải thiện rất nhiều!', 3),

-- Bình luận cho bài 5
(5, 0, NULL, NULL, 'MVC giúp code organized hơn nhiều!', 4),
(5, 0, 'Developer X', 'devx@email.com', 'Có nên dùng framework hay tự code MVC?', NULL),
(5, 14, NULL, NULL, 'Tùy dự án. Dự án nhỏ có thể tự code, dự án lớn nên dùng framework.', 2),

-- Bình luận cho bài 6
(6, 0, NULL, NULL, 'ES6 làm JavaScript đẹp hơn nhiều!', 5),
(6, 0, NULL, NULL, 'Arrow function và destructuring mình dùng hàng ngày!', 1),

-- Bình luận cho bài 7
(7, 0, 'Học viên A', 'hocvien@email.com', 'Git có khó không các bạn?', NULL),
(7, 18, NULL, NULL, 'Ban đầu hơi khó nhưng sau quen thì rất dễ!', 4),
(7, 0, NULL, NULL, 'Bài viết rất chi tiết, cảm ơn!', 5),

-- Bình luận cho bài 8
(8, 0, NULL, NULL, 'Website mình load nhanh hơn nhiều sau khi áp dụng!', 2),
(8, 0, NULL, NULL, 'Lazy loading ảnh thực sự hiệu quả!', 3),

-- Bình luận cho bài 9
(9, 0, 'Mobile Dev', 'mobiledev@email.com', 'RESTful API dùng cho mobile app được không?', NULL),
(9, 23, NULL, NULL, 'Được chứ! RESTful API là tiêu chuẩn cho mọi nền tảng.', 3),

-- Bình luận cho bài 10
(10, 0, NULL, NULL, 'Grid và Flexbox kết hợp rất mạnh!', 4),
(10, 0, NULL, NULL, 'Mình thích Grid hơn vì linh hoạt hơn.', 2);

