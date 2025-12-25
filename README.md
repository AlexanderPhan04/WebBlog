# WebBlog - Hệ thống Blog PHP MVC

Hệ thống blog được xây dựng bằng PHP thuần, không sử dụng framework, áp dụng kiến trúc MVC và lập trình hướng đối tượng (OOP).

## 👨‍💻 Tác giả

**Phan Nhật Quân**

## 🚀 Tính năng

### Người dùng

- ✅ Đăng ký/Đăng nhập tài khoản
- ✅ Xem danh sách bài viết với phân trang
- ✅ Xem chi tiết bài viết
- ✅ Tìm kiếm bài viết theo tiêu đề và tags
- ✅ Bình luận trên bài viết (hỗ trợ bình luận phân cấp/reply)
- ✅ Quản lý bài viết của bản thân (thêm/sửa/xóa)

### Quản trị viên

- ✅ Dashboard với thống kê tổng quan
- ✅ Quản lý người dùng (xem danh sách, phân quyền, xóa)
- ✅ Quản lý bài viết (duyệt/xóa tất cả bài viết)
- ✅ Quản lý bình luận (xem/xóa)

## 🛠️ Công nghệ sử dụng

- **Backend:** PHP 8.x (Pure PHP, No Framework)
- **Database:** MySQL 8.0
- **Frontend:** Bootstrap 5.3, JavaScript
- **Architecture:** MVC Pattern
- **Database Access:** PDO with Prepared Statements
- **Security:** Password hashing (bcrypt), SQL Injection prevention, XSS protection

## 📁 Cấu trúc thư mục

```
WebBlog/
├── app/
│   ├── controllers/          # Controllers
│   │   ├── AdminController.php
│   │   ├── AuthController.php
│   │   ├── HomeController.php
│   │   └── PostController.php
│   ├── core/                 # Core MVC classes
│   │   ├── App.php          # Router
│   │   ├── Controller.php   # Base Controller
│   │   └── Model.php        # Base Model
│   ├── models/              # Models
│   │   ├── Category.php
│   │   ├── Comment.php
│   │   ├── Post.php
│   │   └── User.php
│   └── views/               # Views
│       ├── admin/           # Admin views
│       ├── auth/            # Auth views
│       ├── home/            # Home views
│       ├── layouts/         # Layout templates
│       └── posts/           # Post views
├── config/
│   ├── config.php           # App configuration
│   └── Database.php         # Database connection (Singleton)
├── public/                  # Public directory (Document root)
│   ├── index.php           # Entry point
│   ├── .htaccess           # Apache rewrite rules
│   ├── css/
│   └── js/
├── database.sql            # Database schema & sample data
├── nginx.conf              # Nginx configuration sample
├── NGINX_SETUP.md         # Nginx setup guide
└── README.md

```

## 📋 Yêu cầu hệ thống

- PHP >= 8.0
- MySQL >= 5.7 hoặc MariaDB >= 10.2
- Apache 2.4+ với mod_rewrite HOẶC Nginx 1.18+
- PDO Extension
- Mbstring Extension

## ⚙️ Cài đặt

### 1. Clone/Download dự án

```bash
git clone https://github.com/AlexanderPhan04/webblog.git
cd webblog
```

### 2. Cấu hình Database

#### Tạo database

```bash
mysql -u root -p < database.sql
```

Hoặc import file `database.sql` qua phpMyAdmin.

#### Cấu hình kết nối

Sửa file `config/Database.php`:

```php
private $host = 'localhost';
private $dbname = 'webblog';
private $username = 'root';
private $password = 'your_password';
```

### 3. Cấu hình Web Server

#### Apache (với .htaccess)

Document root cần trỏ đến thư mục `public/`. File `.htaccess` đã có sẵn.

#### Nginx

Xem hướng dẫn chi tiết trong file `NGINX_SETUP.md`.

Cấu hình cơ bản:

```nginx
location / {
    try_files $uri $uri/ /index.php?url=$request_uri;
}

location ~ \.php$ {
    try_files $uri =404;
    fastcgi_pass unix:/tmp/php-cgi-82.sock;
    fastcgi_index index.php;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    include fastcgi_params;
}
```

### 4. Truy cập ứng dụng

- Trang chủ: `http://localhost/WebBlog/public/`
- Đăng nhập: `http://localhost/WebBlog/public/auth/login`
- Admin: `http://localhost/WebBlog/public/admin`

## 🔐 Tài khoản mặc định

### Admin

- Username: `admin`
- Password: `password`

### User

- Username: `nguyenvana`
- Password: `password`

**Lưu ý:** Nên đổi mật khẩu ngay sau khi cài đặt!

## 🗄️ Cấu trúc Database

### Bảng chính

- **users** - Quản lý người dùng
- **posts** - Bài viết
- **comments** - Bình luận (hỗ trợ parent_id cho reply)
- **categories** - Danh mục bài viết

### Quan hệ

- `posts.user_id` → `users.id`
- `posts.category_id` → `categories.id`
- `comments.post_id` → `posts.id`
- `comments.user_id` → `users.id`
- `comments.parent_id` → `comments.id` (self-reference cho reply)

## 🎯 Kiến trúc MVC

### Model

- Kế thừa từ lớp `Model` base class
- Chứa logic xử lý dữ liệu
- Sử dụng PDO với Prepared Statements

### View

- Template PHP thuần
- Không chứa logic xử lý
- Sử dụng Bootstrap 5 cho giao diện

### Controller

- Kế thừa từ lớp `Controller` base class
- Xử lý request và điều hướng
- Gọi Model và truyền dữ liệu cho View

### Router (App.php)

- Xử lý URL routing
- Format: `/controller/method/param1/param2`
- Hỗ trợ cả Apache (.htaccess) và Nginx

## 🔒 Bảo mật

- ✅ Password hashing với `password_hash()` (bcrypt)
- ✅ Prepared Statements chống SQL Injection
- ✅ `htmlspecialchars()` chống XSS
- ✅ Session management
- ✅ CSRF protection (có thể thêm token)
- ✅ Input validation & sanitization
- ✅ Emulate prepares = false trong PDO

## 🌐 Cấu hình Production

### 1. Tắt hiển thị lỗi

Trong `config/config.php`:

```php
error_reporting(0);
ini_set('display_errors', 0);
```

### 2. Bật HTTPS

Cấu hình SSL certificate trong Nginx/Apache.

### 3. Bảo mật thư mục

Đảm bảo chỉ thư mục `public/` được truy cập từ web.

```bash
chmod -R 755 /path/to/WebBlog
chmod -R 644 /path/to/WebBlog/config/*.php
```

## 📝 TODO / Cải tiến

- [ ] Thêm CSRF token protection
- [ ] Upload ảnh cho bài viết
- [ ] Phân quyền chi tiết hơn
- [ ] Cache layer
- [ ] API endpoints
- [ ] Email verification
- [ ] Password reset
- [ ] Social login

## 📄 License

MIT License - Tự do sử dụng cho mục đích học tập và thương mại.

## 📧 Liên hệ

- Author: Phan Nhật Quân
- Website: https://blog.alexstudio.id.vn

---

**Made with ❤️ using Pure PHP**
