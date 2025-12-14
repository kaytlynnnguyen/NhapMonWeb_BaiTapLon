<?php
// Database configuration - chỉnh lại theo môi trường của bạn
// Ví dụ local MySQL: host=127.0.0.1, user=root, pass='', db=jourlestours

// Thiết lập mặc định dùng khi phát triển local
$db_host = 'localhost';
$db_user = 'nguyenhan'; // hoặc 'root'
$db_pass = 'matkhau123'; // mật khẩu MySQL
$db_name = 'jourlestours'; // database đã tạo
$base_url = 'http://localhost/NMW_DOAN';

// Nếu đang chạy trên domain InfinityFree của bạn, override các giá trị trên.
if (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'dh52200624nguyengiahan.infinityfree.me') !== false) {
	// Thay các giá trị bên dưới bằng thông tin MySQL thực tế từ bảng quản trị InfinityFree
	// Thông thường user DB và tên DB bắt đầu bằng 'if0_...'
	$db_host = 'sql210.infinityfree.com'; // <-- sửa thành host MySQL do InfinityFree cung cấp
	$db_user = 'if0_40671780'; // bạn đã cung cấp
	$db_pass = 'C9MPhAO3DMHEvZQ'; // bạn đã cung cấp
	$db_name = 'if0_40671780_data'; // <-- sửa theo DB bạn đã tạo trên InfinityFree
	$base_url = 'https://dh52200624nguyengiahan.infinityfree.me'; // sửa thành domain của bạn trên InfinityFree
}

// Định nghĩa hằng số dùng ở toàn project
define('DB_HOST', $db_host);
define('DB_USER', $db_user);
define('DB_PASS', $db_pass);
define('DB_NAME', $db_name);
define('BASE_URL', $base_url);

// Nếu bạn không muốn dùng MySQL, để DB_HOST thành chuỗi rỗng ''.
