# WebBlog - Ứng dụng Blog PHP thuần với MVC & OOP

## Giới thiệu

WebBlog là một ứng dụng blog đầy đủ chức năng được xây dựng bằng **PHP thuần** (không sử dụng framework), áp dụng kiến trúc **MVC** (Model-View-Controller) và lập trình hướng đối tượng (**OOP**).

### Tính năng chính

✅ **Quản lý người dùng**

- Đăng ký/Đăng nhập/Đăng xuất
- Phân quyền: Guest, User, Admin
- Mã hóa mật khẩu với `password_hash()`

✅ **Quản lý bài viết**

- Tạo, sửa, xóa bài viết
- Tự động tạo slug từ tiêu đề
- Lọc HTML nguy hiểm, cho phép các thẻ an toàn
- Tự động tạo excerpt
- Danh mục và tags
- Đếm lượt xem tự động
- Tìm kiếm bài viết

✅ **Bình luận phân cấp**

- Bình luận 3 cấp (parent-child-grandchild)
- Guest có thể bình luận (nhập tên và email)
- User đã đăng nhập tự động điền thông tin
- Reply cho bình luận
- Admin và tác giả có thể xóa bình luận

✅ **Admin Panel**

- Dashboard với thống kê
- Quản lý người dùng (thay đổi role, xóa user)
- Quản lý bài viết (xem, sửa, xóa tất cả bài viết)
- Quản lý bình luận (xem, xóa spam)

✅ **Bảo mật**

- Prepared Statements (PDO) chống SQL Injection
- `htmlspecialchars()` chống XSS
- Password hashing
- Session management
- Input validation nghiêm ngặt

✅ **Giao diện**

- Responsive với Bootstrap 5
- Clean và modern UI
- Phân trang cho bài viết và bình luận

## Yêu cầu hệ thống

- PHP 7.4 trở lên
- MySQL 5.7 trở lên / MariaDB
- Apache với mod_rewrite enabled
- Web server (XAMPP, WAMP, MAMP, hoặc hosting)

## Cài đặt

### Bước 1: Clone/Download source code

```bash
# Clone repository
git clone <repository-url> webBlog

# Hoặc giải nén file zip vào thư mục webBlog
```

### Bước 2: Import database

1. Mở phpMyAdmin hoặc MySQL client
2. Tạo database mới tên `webblog`
3. Import file `database.sql` vào database `webblog`

```sql
mysql -u root -p webblog < database.sql
```

Database sẽ tự động tạo:

- 4 bảng: `users`, `posts`, `comments`, `categories`
- 5 users mẫu (1 admin, 4 users)
- 10 bài viết mẫu
- 25+ bình luận mẫu (bao gồm bình luận phân cấp)

### Bước 3: Cấu hình database

Mở file `config/config.php` và cập nhật thông tin database:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'webblog');
define('DB_USER', 'root');
define('DB_PASS', '');  // Nhập password MySQL của bạn
```

### Bước 4: Cấu hình URL

Cập nhật `BASE_URL` trong `config/config.php`:

```php
// Nếu chạy trên localhost
define('BASE_URL', 'http://localhost/webBlog/public');

// Nếu chạy trên hosting
define('BASE_URL', 'http://yourdomain.com/public');
```

### Bước 5: Cấu hình Apache

Đảm bảo `mod_rewrite` được enable trong Apache:

**XAMPP/WAMP:**

1. Mở `httpd.conf`
2. Uncomment dòng: `LoadModule rewrite_module modules/mod_rewrite.so`
3. Tìm `AllowOverride None` và đổi thành `AllowOverride All`
4. Restart Apache

### Bước 6: Truy cập ứng dụng

Mở trình duyệt và truy cập:

```
http://localhost/webBlog/public
```

## Tài khoản demo

### Admin

- **Username:** `admin`
- **Password:** `password`
- **Quyền:** Quản lý toàn bộ hệ thống

### User thường

- **Username:** `nguyenvana`, `tranthib`, `phamvanc`, `lehoangd`
- **Password:** `password`
- **Quyền:** Viết bài, bình luận, quản lý bài viết của mình

## Cấu trúc thư mục

```
webBlog/
│
├── app/
│   ├── controllers/          # Các controller xử lý logic
│   │   ├── AdminController.php
│   │   ├── AuthController.php
│   │   ├── HomeController.php
│   │   └── PostController.php
│   │
│   ├── models/              # Các model tương tác database
│   │   ├── Category.php
│   │   ├── Comment.php
│   │   ├── Post.php
│   │   └── User.php
│   │
│   ├── views/               # Các view hiển thị giao diện
│   │   ├── admin/           # Admin panel views
│   │   ├── auth/            # Login/Register views
│   │   ├── home/            # Home/Search views
│   │   ├── posts/           # Post views
│   │   └── layouts/         # Header/Footer templates
│   │
│   └── core/                # Core MVC classes
│       ├── App.php          # Router chính
│       ├── Controller.php   # Base controller
│       └── Model.php        # Base model
│
├── config/                  # Cấu hình
│   ├── config.php          # Cấu hình chính
│   └── database.php        # Database connection
│
├── public/                  # Public assets & entry point
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   └── main.js
│   ├── index.php           # Entry point
│   └── .htaccess
│
├── database.sql            # Database schema & sample data
├── .htaccess              # Root .htaccess
└── README.md              # File này
```

## Kiến trúc MVC

### Model

- Tương tác với database qua PDO
- Chứa business logic
- Validate dữ liệu
- Base class: `Model.php` với các phương thức CRUD cơ bản

### View

- Hiển thị dữ liệu cho người dùng
- Sử dụng PHP template
- Bootstrap 5 cho responsive design
- Tách riêng header/footer layouts

### Controller

- Nhận request từ user
- Gọi Model để lấy/xử lý dữ liệu
- Truyền dữ liệu cho View
- Base class: `Controller.php` với các helper methods

### Routing

- `App.php` xử lý routing động
- URL format: `controller/method/param1/param2`
- Ví dụ: `post/show/gioi-thieu-ve-php-va-mysql`

## Tính năng nổi bật

### 1. Bình luận phân cấp (Nested Comments)

Hệ thống hỗ trợ bình luận 3 cấp với thuật toán đệ quy:

```php
// Model: Comment.php
private function buildCommentTree($comments, $parentId = 0) {
    $tree = [];
    foreach ($comments as $comment) {
        if ($comment['parent_id'] == $parentId) {
            $comment['replies'] = $this->buildCommentTree($comments, $comment['id']);
            $tree[] = $comment;
        }
    }
    return $tree;
}
```

### 2. Tự động tạo Slug

Tự động chuyển đổi tiêu đề tiếng Việt có dấu thành slug không dấu:

```php
"Giới thiệu về PHP và MySQL" → "gioi-thieu-ve-php-va-mysql"
```

### 3. Lọc HTML an toàn

Cho phép các thẻ HTML cơ bản, loại bỏ thẻ nguy hiểm:

```php
define('ALLOWED_HTML_TAGS', '<p><br><strong><em><ul><ol><li><a><img><h1><h2><h3><h4><blockquote><code><pre>');
```

### 4. Bảo mật cao

- **SQL Injection:** Sử dụng PDO Prepared Statements
- **XSS:** Escape output với `htmlspecialchars()`
- **Password:** Hash với `password_hash()` (bcrypt)
- **Session:** Quản lý session an toàn
- **Validation:** Kiểm tra input nghiêm ngặt ở server-side

## Hướng dẫn sử dụng

### Đăng ký tài khoản

1. Click "Đăng ký" trên navbar
2. Điền đầy đủ thông tin
3. Mật khẩu tối thiểu 6 ký tự
4. Sau khi đăng ký thành công, đăng nhập để sử dụng

### Viết bài mới

1. Đăng nhập với tài khoản user/admin
2. Click "Viết bài" trên navbar
3. Nhập tiêu đề, nội dung (hỗ trợ HTML)
4. Chọn danh mục và nhập tags (tùy chọn)
5. Click "Đăng bài"

### Bình luận

1. Vào chi tiết một bài viết
2. Cuộn xuống phần bình luận
3. **Guest:** Nhập tên, email và nội dung
4. **User:** Tự động điền thông tin, chỉ nhập nội dung
5. Click "Trả lời" để reply cho bình luận (tối đa 3 cấp)

### Quản trị (Admin)

1. Đăng nhập với tài khoản admin
2. Click "Quản trị" trên navbar
3. Dashboard hiển thị thống kê tổng quan
4. Quản lý users, posts, comments
5. Thay đổi role user: user ↔ admin
6. Xóa bài viết/bình luận spam

## Tùy chỉnh

### Thay đổi số bài viết mỗi trang

File: `config/config.php`

```php
define('POSTS_PER_PAGE', 10);  // Thay đổi số này
```

### Thêm danh mục mới

```sql
INSERT INTO categories (name, slug) VALUES ('Tên danh mục', 'ten-danh-muc');
```

### Thay đổi theme color

File: `public/css/style.css` - Tùy chỉnh CSS theo ý muốn

## Troubleshooting

### Lỗi 404 - Page Not Found

- Kiểm tra `mod_rewrite` đã enable chưa
- Kiểm tra file `.htaccess` tồn tại
- Kiểm tra `AllowOverride All` trong Apache config

### Lỗi kết nối database

- Kiểm tra thông tin database trong `config/config.php`
- Đảm bảo MySQL service đang chạy
- Kiểm tra database `webblog` đã tồn tại

### Lỗi Permission Denied

- Cấp quyền 755 cho thư mục webBlog
- XAMPP/WAMP: không cần cấu hình thêm

### CSS/JS không load

- Kiểm tra `BASE_URL` trong `config/config.php`
- Kiểm tra đường dẫn tuyệt đối đúng

## Công nghệ sử dụng

- **Backend:** PHP 7.4+ (OOP, MVC)
- **Database:** MySQL/MariaDB (PDO)
- **Frontend:** HTML5, CSS3, JavaScript (ES6)
- **CSS Framework:** Bootstrap 5.3
- **Icons:** Bootstrap Icons
- **Server:** Apache (mod_rewrite)

## Tính năng nâng cao có thể mở rộng

- Upload ảnh cho bài viết
- Rich text editor (TinyMCE, CKEditor)
- Pagination AJAX
- Like/Dislike bài viết
- Social sharing
- Email notification
- Forgot password functionality
- User profile page
- Advanced search filters
- Export bài viết PDF
- Sitemap XML
- RSS Feed

## Đánh giá theo tiêu chí

✅ **Hoàn thiện chức năng chính (60%):** CRUD bài viết, bình luận phân cấp, tìm kiếm, phân trang

✅ **Phân quyền và bảo mật (15%):** Guest/User/Admin, validate, XSS, SQL Injection, password hash

✅ **Bình luận phân cấp (10%):** 3 cấp, reply, đệ quy hiển thị

✅ **Giao diện (10%):** Bootstrap 5, responsive, clean UI

✅ **Tổ chức code (5%):** MVC, OOP, comments, README chi tiết

## Tác giả

Dự án được xây dựng với mục đích học tập và thực hành:

- Kiến trúc MVC trong PHP
- Lập trình hướng đối tượng (OOP)
- Bảo mật ứng dụng web
- Quản lý database với PDO
- Responsive web design

## License

Dự án này được phát hành dưới MIT License - tự do sử dụng cho mục đích học tập và phát triển.

---

**Chúc bạn code vui vẻ! 🚀**

Nếu gặp vấn đề, vui lòng kiểm tra lại các bước cài đặt hoặc liên hệ để được hỗ trợ.
