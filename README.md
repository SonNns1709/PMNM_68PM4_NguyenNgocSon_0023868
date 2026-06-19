# PMNM_68PM4_NguyenNgocSon_0023868

# Hệ Thống Quản Lý Sinh Viên

> **Học phần:** Phần mềm Nguồn Mở &nbsp;|&nbsp; **Lớp:** 68PM4  
> **Sinh viên:** Nguyễn Ngọc Sơn &nbsp;|&nbsp; **MSSV:** 0023868

---

## 📖 Mô Tả

Hệ thống Quản lý Sinh Viên được xây dựng theo mô hình **MVC thuần PHP** (không framework), kết hợp với **MySQL** và **Bootstrap 5** theo phong cách giao diện **Dark Psychology**. Đây là dự án thực hành cho học phần Phần mềm Nguồn Mở, tập trung vào việc xây dựng ứng dụng web hoàn chỉnh từ đầu với kiến trúc rõ ràng, dễ mở rộng.

### Công nghệ sử dụng

| Thành phần   | Công nghệ                              |
| :----------- | :------------------------------------- |
| Ngôn ngữ     | PHP 8.x (thuần MVC, không framework)   |
| Database     | MySQL 8.x / MariaDB 10.x               |
| Frontend     | Bootstrap 5.3, Custom CSS (Dark Theme) |
| Server       | Apache (XAMPP)                         |
| Phiên bản    | PHP PDO để kết nối database            |
| Quản lý code | Git + GitHub                           |

---

## ⚙️ Cài Đặt

### Yêu cầu hệ thống

- XAMPP (Apache + MySQL + PHP 8.x)
- Git
- Trình duyệt web hiện đại

### Các bước cài đặt

**1. Clone repository về máy**

```bash
cd C:\xampp\htdocs\PMNM_68PM4_NguyenNgocSon_0023868
git clone https://github.com/SonNns1709/QuanLySinhVien.git
```

**2. Import database**

Mở `http://localhost/phpmyadmin` → Tạo database `68PM34` → Tab SQL → Chạy lệnh:

```sql
-- Tạo các bảng
CREATE TABLE tbl_sinhviens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hoten VARCHAR(100) NOT NULL,
    gioitinh VARCHAR(10) NOT NULL,
    mssv VARCHAR(20) NOT NULL UNIQUE,
    nganh VARCHAR(100) DEFAULT 'Chưa xác định'
);

CREATE TABLE tbl_monhocs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ma_mon VARCHAR(20) NOT NULL UNIQUE,
    ten_mon VARCHAR(100) NOT NULL,
    so_tin_chi INT NOT NULL DEFAULT 3
);

CREATE TABLE tbl_diems (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sinhvien_id INT NOT NULL,
    monhoc_id INT NOT NULL,
    diem_chuyen_can FLOAT DEFAULT 0,
    diem_giua_ky FLOAT DEFAULT 0,
    diem_cuoi_ky FLOAT DEFAULT 0,
    diem_tong_ket FLOAT DEFAULT 0,
    FOREIGN KEY (sinhvien_id) REFERENCES tbl_sinhviens(id) ON DELETE CASCADE,
    FOREIGN KEY (monhoc_id) REFERENCES tbl_monhocs(id),
    UNIQUE(sinhvien_id, monhoc_id)
);

CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'user',
    name VARCHAR(100)
);

-- Tài khoản admin mặc định
INSERT INTO user (username, password, role, name)
VALUES ('admin', '123456', 'admin', 'Quản trị viên');
```

**3. Cấu hình kết nối database**

Mở file `app/config.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', '68PM34');
define('DB_USER', 'root');
define('DB_PASS', '');
```

Mở file `app/middleware.php`, cập nhật `BASE_URL`:

```php
define('BASE_URL', '/PMNM_68PM4_NguyenNgocSon_0023868/QuanLySinhVien/public');
```

**4. Khởi động XAMPP**

- Mở XAMPP Control Panel
- Nhấn **Start** cho **Apache** và **MySQL**
- Truy cập: `http://localhost/PMNM_68PM4_NguyenNgocSon_0023868/QuanLySinhVien/public`

**5. Đăng nhập**

- Username: `admin`
- Password: `123456`

---

## 🗂️ Cấu Trúc Thư Mục

```
QuanLySinhVien/
├── app/
│   ├── config.php               # Cấu hình DB
│   ├── middleware.php           # Bảo vệ route (Session)
│   ├── controllers/
│   │   ├── home.php             # Trang chủ
│   │   ├── authen.php           # Đăng nhập / Đăng xuất
│   │   ├── sinhvien.php         # CRUD sinh viên
│   │   └── thongke.php          # Thống kê
│   ├── core/
│   │   ├── App.php              # Router (Thầy cung cấp)
│   │   ├── Controller.php       # Base controller
│   │   ├── Model.php            # Base model (PDO)
│   │   └── ConnectDB.php        # Singleton kết nối DB
│   ├── models/
│   │   ├── sinhvienModel.php    # Model sinh viên
│   │   └── thongkeModel.php     # Model thống kê
│   └── views/
│       ├── layout/
│       │   ├── masterlayout.php # Layout chính
│       │   └── partial/
│       │       ├── header.php   # Navbar + Flash message
│       │       └── footer.php   # Footer chung
│       ├── home/
│       │   ├── index.php        # Trang chủ
│       │   └── login.php        # Đăng nhập
│       ├── sinhvien/
│       │   ├── index.php        # Danh sách + Search + Paging
│       │   ├── create.php       # Thêm sinh viên + nhập điểm
│       │   ├── edit.php         # Sửa sinh viên + cập nhật điểm
│       │   └── diem.php         # Bảng điểm chi tiết
│       └── thongke/
│           └── index.php        # Trang thống kê
├── public/
│   ├── assets/css/style.css     # Dark Psychology theme
│   ├── .htaccess                # URL Rewriting
│   └── index.php                # Entry point
└── README.md
```

---

## ✨ Chức Năng

### 🔐 Xác thực

- [x] Đăng nhập bằng username/password
- [x] Bảo vệ tất cả route bằng Session Middleware
- [x] Đăng xuất, huỷ session
- [x] Redirect về login khi chưa đăng nhập

### 👥 Quản Lý Sinh Viên (CRUD)

- [x] **Xem danh sách** sinh viên dạng bảng Bootstrap
- [x] **Thêm sinh viên** với form Bootstrap (họ tên, MSSV, giới tính, ngành)
- [x] **Nhập điểm** CC/GK/CK cho từng môn khi thêm/sửa (tự tính Tổng kết)
- [x] **Sửa thông tin** với form pre-fill dữ liệu cũ
- [x] **Xóa sinh viên** với xác nhận JavaScript
- [x] **Flash Message** thông báo thành công sau mỗi thao tác

### 🔍 Tìm Kiếm & Lọc

- [x] Tìm kiếm theo **Họ tên** hoặc **MSSV**
- [x] Lọc theo **Ngành học**
- [x] Lọc theo **Xếp loại** (Xuất Sắc / Giỏi / Khá / Trung Bình / Yếu)
- [x] Kết hợp nhiều bộ lọc cùng lúc

### 📄 Phân Trang

- [x] Phân trang với **LIMIT / OFFSET** (5 SV/trang)
- [x] Giữ nguyên bộ lọc khi chuyển trang
- [x] Hiển thị thông tin "Trang X / Y"

### 📊 GPA & Bảng Điểm

- [x] Tính **GPA tích lũy** có trọng số theo tín chỉ
- [x] Cột **Xếp loại** màu sắc trong danh sách
- [x] **Bảng điểm chi tiết** từng môn (CC / GK / CK / Tổng)
- [x] Trạng thái **Đạt / Học lại** mỗi môn (ngưỡng 4.0)
- [x] Màu xanh (≥5) / đỏ (<5) cho điểm số

### 📈 Thống Kê

- [x] Tổng số sinh viên
- [x] GPA cao nhất / thấp nhất / trung bình toàn trường
- [x] Thống kê từng ngành: số SV + GPA TB
- [x] Phân bổ xếp loại học lực

### 🎨 Giao Diện

- [x] **Dark Psychology Theme** (toàn bộ ứng dụng)
- [x] Bootstrap 5 Responsive (tương thích điện thoại)
- [x] Navbar chung với link đến tất cả tính năng
- [x] Stat Cards động trong trang Thống kê

---

## 📸 Ảnh Chụp Màn Hình

> _(Thêm ảnh chụp màn hình các trang chính vào đây)_

| Trang                 | Mô tả                                |
| :-------------------- | :----------------------------------- |
| `/authen/login`       | Trang đăng nhập với Dark theme       |
| `/home/index`         | Trang chủ với Quick-action cards     |
| `/sinhvien/index`     | Danh sách + Search + Paging + Filter |
| `/sinhvien/create`    | Form thêm SV + bảng nhập điểm        |
| `/sinhvien/diem/{id}` | Bảng điểm chi tiết từng môn          |
| `/thongke/index`      | Trang thống kê với Stat Cards        |

---

## 🏗️ Kiến Trúc MVC

```
Request URL
    ↓
public/index.php (session_start + load App.php)
    ↓
App.php (Router — phân tích URL → Controller/Action/Params)
    ↓
Middleware::protect() (kiểm tra Session)
    ↓
Controller (gọi Model + truyền data → View)
    ↓
masterlayout.php (header + [view động] + footer)
    ↓
Response HTML
```

### Design Patterns sử dụng

- **MVC Pattern** — tách biệt Model, View, Controller
- **Singleton Pattern** — ConnectDB chỉ tạo 1 kết nối PDO duy nhất
- **Front Controller Pattern** — toàn bộ request qua public/index.php
- **Master-Detail Pattern** — danh sách SV → bảng điểm chi tiết

---

## 📝 Lịch Sử Commit

| Commit                                               | Nội dung                              |
| :--------------------------------------------------- | :------------------------------------ |
| `feat: Khoi tao Project`                             | Khởi tạo cấu trúc app/, public/       |
| `feat: URL Process + MVC`                            | App.php router, Controller/Model base |
| `feat: add login + middleware`                       | Xác thực session, middleware          |
| `feat <core>: add Controller + ConnectDB`            | Base classes, PDO connection          |
| `feat <model>: add SinhvienModel::getAll()`          | Model sinh viên                       |
| `feat: Hien thi danh sach sinh vien`                 | View danh sách                        |
| `feat <UI>: add layoutmaster, Partial`               | masterlayout, header, footer          |
| `feat <sinhvien>: create Sinhvien`                   | Thêm sinh viên                        |
| `feat <sinhvien>: Paging`                            | Phân trang + tìm kiếm                 |
| `feat <sinhvien>: update sinhvien`                   | Sửa thông tin                         |
| `feat <sinhvien>: delete sinhvien`                   | Xóa sinh viên                         |
| `feat <config>: add config DB`                       | Tập trung cấu hình DB                 |
| `feat <sinhvien>: add GPA + xep loai + filter`       | GPA, xếp loại, bộ lọc                 |
| `feat <sinhvien>: add bang diem chi tiet`            | Bảng điểm Master-Detail               |
| `feat <sinhvien>: add nganh + diem to create edit`   | Form nhập điểm, ngành                 |
| `feat <UI>: Bootstrap table form + nganh col`        | Bootstrap styling                     |
| `feat <sinhvien>: add nganh filter dropdown`         | Lọc theo ngành                        |
| `feat <thongke>: add thong ke controller model view` | Trang thống kê                        |
| `feat: flash message + navbar + home view`           | Flash, Navbar, Home                   |
| `feat <UI>: dark psychology theme CSS`               | Dark Psychology theme                 |
| `docs: add README.md`                                | Tài liệu dự án                        |

---

## 👨‍💻 Thông Tin Sinh Viên

| Thông tin     | Chi tiết                                                   |
| :------------ | :--------------------------------------------------------- |
| **Họ và tên** | Nguyễn Ngọc Sơn                                            |
| **MSSV**      | 0023868                                                    |
| **Lớp**       | 68PM4                                                      |
| **Học phần**  | Phần mềm Nguồn Mở                                          |
| **GitHub**    | [github.com/SonNns1709/QuanLySinhVien](https://github.com) |

---

> _"The best code is the code that is easy to understand, maintain, and extend."_
