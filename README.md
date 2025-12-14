# Jour Les Tours - Trang Web Bánh Ngọt & Bánh Mặn

Trang web bán hàng bánh ngọt và bánh mặn với thiết kế hiện đại, màu hồng chủ đạo.

## Cấu trúc dự án

```
NMW_DOAN/
├── index.php              # Trang chủ chính
├── assets/
│   ├── css/
│   │   └── style.css      # File CSS chính
│   ├── js/
│   │   └── main.js        # File JavaScript cho các chức năng tương tác
│   └── images/            # Thư mục chứa hình ảnh sản phẩm
└── README.md              # File hướng dẫn này
```

## Tính năng

1. **Header với Navigation**
   - Logo "Jour Les Tours"
   - Menu điều hướng (Menu tổng hợp, Bánh mặn, Bánh ngọt, Top bán chạy)
   - Icon giỏ hàng với số lượng sản phẩm
   - Icon tìm kiếm

2. **Hero Section**
   - Ảnh nền đẹp mắt
   - Tiêu đề và mô tả
   - Nút "Khám phá menu"

3. **Top Bán Chạy Section**
   - Hiển thị 3 sản phẩm bán chạy nhất
   - Badge "Bán chạy" trên mỗi sản phẩm
   - Thông tin chi tiết và nút thêm vào giỏ hàng

4. **Menu Tổng Hợp Section**
   - Sidebar filter với các bộ lọc:
     - Loại bánh (Tất cả, Bánh ngọt, Bánh mặn)
     - Dòng sản phẩm (Macaron, Bánh kem, Mousse, v.v.)
     - Mức giá (Dưới 50k, 50k-100k, 100k-200k, Trên 200k)
   - Grid hiển thị tất cả sản phẩm
   - Lọc sản phẩm theo điều kiện

5. **Footer**
   - Thông tin liên hệ (địa chỉ, điện thoại, website)
   - Giờ hoạt động
   - Mạng xã hội
   - Thông tin khác (chính sách, FAQ, v.v.)

## Responsive Design

Trang web được thiết kế responsive, tự động điều chỉnh cho các màn hình:
- Desktop (992px trở lên)
- Tablet (768px - 992px)
- Mobile (dưới 768px)

## Cách sử dụng

1. Đặt các file hình ảnh sản phẩm vào thư mục `assets/images/`
2. Tên file ảnh phải khớp với tên trong mảng `$products` trong `index.php`
3. Chạy trang web bằng XAMPP, WAMP, hoặc PHP built-in server:
   ```bash
   php -S localhost:9000
   ```
4. Truy cập `http://localhost:9000` trong trình duyệt

## Tùy chỉnh

### Thay đổi sản phẩm
Chỉnh sửa mảng `$products` trong file `index.php` để thêm/xóa/sửa sản phẩm.

### Thay đổi màu sắc
Chỉnh sửa các biến CSS trong file `assets/css/style.css`:
- `--primary-pink`: Màu hồng chính
- `--dark-pink`: Màu hồng đậm
- `--light-pink`: Màu hồng nhạt

### Thêm chức năng
- Chức năng giỏ hàng: Mở rộng hàm `addToCart()` trong `assets/js/main.js`
- Kết nối database: Tạo file `config.php` và kết nối MySQL/PostgreSQL
- Xử lý form: Tạo file PHP để xử lý đặt hàng

## Lưu ý

- Đảm bảo các file hình ảnh tồn tại trong thư mục `assets/images/`
- Nếu thiếu ảnh, trình duyệt sẽ hiển thị placeholder
- Tất cả giá sản phẩm được hiển thị bằng VNĐ (₫)

