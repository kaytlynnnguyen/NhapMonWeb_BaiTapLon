# Hướng Dẫn Chạy Website Jour Les Tours

## Cách 1: Sử dụng PHP Built-in Server (Đơn giản nhất)

### Bước 1: Mở Terminal/Command Prompt
- Nhấn `Windows + R`, gõ `cmd` hoặc `powershell` và nhấn Enter
- Hoặc mở VS Code Terminal (Ctrl + `)

### Bước 2: Di chuyển đến thư mục dự án
```bash
cd E:\NMW_DOAN
```

### Bước 3: Chạy server PHP
```bash
php -S localhost:9000
```

### Bước 4: Mở trình duyệt
- Truy cập: `http://localhost:9000`
- Hoặc: `http://127.0.0.1:9000`

### Lưu ý:
- Giữ cửa sổ Terminal mở khi đang chạy server
- Để dừng server: Nhấn `Ctrl + C` trong Terminal

---

## Cách 2: Sử dụng XAMPP (Phù hợp cho phát triển dài hạn)

### Bước 1: Tải và cài đặt XAMPP
- Tải tại: https://www.apachefriends.org/
- Cài đặt XAMPP

### Bước 2: Copy project vào thư mục htdocs
- Copy thư mục `NMW_DOAN` vào: `C:\xampp\htdocs\`

### Bước 3: Khởi động XAMPP
- Mở XAMPP Control Panel
- Start **Apache** service

### Bước 4: Truy cập website
- Mở trình duyệt: `http://localhost/NMW_DOAN`
- Hoặc: `http://localhost/NMW_DOAN/index.php`

---

## Cách 3: Sử dụng Laragon (Windows - Khuyến nghị)

### Bước 1: Tải và cài đặt Laragon
- Tải tại: https://laragon.org/
- Cài đặt Laragon

### Bước 2: Copy project vào www
- Copy thư mục `NMW_DOAN` vào: `C:\laragon\www\`

### Bước 3: Khởi động Laragon
- Mở Laragon
- Click **Start All**

### Bước 4: Truy cập website
- Mở trình duyệt: `http://nmw-doan.test`
- Hoặc: `http://localhost/NMW_DOAN`

---

## Cách 4: Sử dụng VS Code (Nhanh nhất trong môi trường dev)

### Bước 1: Mở VS Code Terminal
- Nhấn `Ctrl + ` (backtick) để mở Terminal
- Hoặc: View → Terminal

### Bước 2: Chạy lệnh
```bash
php -S localhost:9000
```

### Bước 3: Mở trình duyệt
- VS Code sẽ tự động mở nếu có cấu hình trong `launch.json`
- Hoặc mở thủ công: `http://localhost:9000`

---

## Kiểm tra nếu gặp lỗi

### Lỗi: Port đã được sử dụng
```bash
# Kiểm tra port 9000 có đang được dùng không
netstat -ano | findstr :9000

# Hoặc đổi sang port khác
php -S localhost:9001
```

### Lỗi: PHP không được nhận diện
- Đảm bảo PHP đã được thêm vào PATH
- Kiểm tra: `php -v` trong Command Prompt

### Lỗi: Không tìm thấy file
- Đảm bảo đang ở đúng thư mục dự án
- Kiểm tra file `index.php` có tồn tại không

---

## Tips

1. **Tự động mở trình duyệt**: Cấu hình trong `.vscode/launch.json` đã sẵn sàng
2. **Hot reload**: PHP built-in server tự động reload khi có thay đổi
3. **Debug**: Cài đặt extension PHP Debug trong VS Code để debug dễ dàng hơn

---

## Cấu trúc thư mục cần có

```
NMW_DOAN/
├── index.php              ✅ Phải có
├── assets/
│   ├── css/
│   │   └── style.css      ✅ Phải có
│   ├── js/
│   │   └── main.js        ✅ Phải có
│   └── images/            📁 Thư mục chứa ảnh (tùy chọn)
└── README.md
```

---

## Hỗ trợ

Nếu gặp vấn đề, kiểm tra:
- ✅ PHP đã được cài đặt chưa? (`php -v`)
- ✅ Đang ở đúng thư mục dự án chưa?
- ✅ Port 9000 có đang được sử dụng không?
- ✅ File `index.php` có tồn tại không?

