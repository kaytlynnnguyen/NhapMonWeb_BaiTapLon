<?php
// Functions file - Contains common functions used across the application

// Load optional DB config
if (file_exists(__DIR__ . '/config.php')) {
    require_once __DIR__ . '/config.php';
}

/**
 * Get all products data
 * In a real application, this would fetch from database
 */
function getProducts() {
    return [
        [
            'id' => 1,
            'name' => 'Bánh Kem Dâu Tây Deluxe',
            'category' => 'BÁNH KEM',
            'description' => 'Bánh kem tươi với lớp kem mềm mịn và dâu tây tươi ngon',
            'price' => 450000,
            'image' => 'Dau-tay-deluxe.jpg',
            'best_seller' => true,
            'type' => 'ngot',
            'line' => 'banh-kem'
        ],
        [
            'id' => 2,
            'name' => 'Macaron Hộp 12 Chiếc',
            'category' => 'MACARON',
            'description' => 'Macaron Pháp truyền thống với nhiều hương vị thơm ngon',
            'price' => 280000,
            'image' => 'macaron.jpg',
            'best_seller' => true,
            'type' => 'ngot',
            'line' => 'macaron'
        ],
        [
            'id' => 3,
            'name' => 'Mousse Chocolate Cao Cấp',
            'category' => 'MOUSSE & CAKE',
            'description' => 'Mousse chocolate đậm đà với lớp bánh mềm mịn',
            'price' => 320000,
            'image' => 'banh-mousse.jpg',
            'best_seller' => false,
            'type' => 'ngot',
            'line' => 'mousse'
        ],
        [
            'id' => 4,
            'name' => 'Croissant Bơ Pháp',
            'category' => 'CROISSANT',
            'description' => 'Croissant bơ Pháp giòn tan, thơm ngon đúng chuẩn',
            'price' => 45000,
            'image' => 'banh-sung-bo.jpg',
            'best_seller' => true,
            'type' => 'ngot',
            'line' => 'croissant'
        ],
        [
            'id' => 5,
            'name' => 'Bánh Tart Trứng Hồng Kông',
            'category' => 'CUSTARD',
            'description' => 'Bánh tart trứng thơm béo, vỏ giòn tan nhân custard mềm mịn',
            'price' => 35000,
            'image' => 'tart_trung.jpg',
            'best_seller' => true,
            'type' => 'ngot',
            'line' => 'custard'
        ],
        [
            'id' => 6,
            'name' => 'Bánh Mì Sandwich Gà',
            'category' => 'SANDWICH',
            'description' => 'Sandwich gà nướng thơm lừng với rau củ tươi ngon',
            'price' => 85000,
            'image' => 'sandwidch-ga.jpg',
            'best_seller' => false,
            'type' => 'man',
            'line' => 'sandwich'
        ],
        [
            'id' => 7,
            'name' => 'Pizza Mini Margherita',
            'category' => 'PIZZA',
            'description' => 'Pizza mini cổ điển với phô mai và cà chua tươi',
            'price' => 120000,
            'image' => 'Margherita.jpg',
            'best_seller' => false,
            'type' => 'man',
            'line' => 'pizza'
        ],
        [
            'id' => 8,
            'name' => 'Bánh Mì Pate Gan',
            'category' => 'SANDWICH',
            'description' => 'Bánh mì pate gan truyền thống Việt Nam',
            'price' => 65000,
            'image' => 'pate-gan.jpg',
            'best_seller' => false,
            'type' => 'man',
            'line' => 'sandwich'
        ],
        [
            'id' => 9,
            'name' => 'Quiche Lorraine',
            'category' => 'QUICHE',
            'description' => 'Quiche thịt xông khói và phô mai kiểu Pháp',
            'price' => 95000,
            'image' => 'Quiche-Lorraine.jpg',
            'best_seller' => false,
            'type' => 'man',
            'line' => 'quiche'
        ],
        [
            'id' => 10,
            'name' => 'Empanada Thịt Bò',
            'category' => 'EMPANADA',
            'description' => 'Bánh empanada nhân thịt bò thơm ngon',
            'price' => 75000,
            'image' => 'Empanada-thit-bo.jpg',
            'best_seller' => false,
            'type' => 'man',
            'line' => 'empanada'
        ],
        [
            'id' => 11,
            'name' => 'Sausage Roll',
            'category' => 'SAUSAGE ROLL',
            'description' => 'Bánh xúc xích kiểu Anh giòn tan',
            'price' => 55000,
            'image' => 'SausageRoll.jpg',
            'best_seller' => false,
            'type' => 'man',
            'line' => 'sausage-roll'
        ],
        [
            'id' => 12,
            'name' => 'Bánh Patso Gà Cà Ri',
            'category' => 'BÁNH PATSO',
            'description' => 'Bánh patso nhân gà cà ri đậm đà',
            'price' => 80000,
            'image' => 'Patso-ga-ca-ri.jpg',
            'best_seller' => false,
            'type' => 'man',
            'line' => 'patso'
        ],
        [
            'id' => 13,
            'name' => 'Focaccia Olive',
            'category' => 'FOCACCIA',
            'description' => 'Bánh focaccia với ô liu và thảo mộc',
            'price' => 90000,
            'image' => 'Focaccia Olive.jpg',
            'best_seller' => false,
            'type' => 'man',
            'line' => 'focaccia'
        ]
    ];
}

/**
 * Get product by ID
 */
function getProductById($id) {
    $products = getProducts();
    foreach ($products as $product) {
        if ($product['id'] == $id) {
            return $product;
        }
    }
    return null;
}

/**
 * Format price for display
 */
function formatPrice($price) {
    return number_format($price, 0, ',', '.');
}

/**
 * Get cart count
 */
function getCartCount() {
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        return 0;
    }
    $count = 0;
    foreach ($_SESSION['cart'] as $item) {
        $count += $item['quantity'];
    }
    return $count;
}

/**
 * Get cart total
 */
function getCartTotal() {
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        return 0;
    }
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $product = getProductById($item['product_id']);
        if ($product) {
            $total += $product['price'] * $item['quantity'];
        }
    }
    return $total;
}

/**
 * User management (simple file-based storage for demo)
 */
function getUsers() {
    $file = __DIR__ . DIRECTORY_SEPARATOR . 'users.json';
    if (!file_exists($file)) {
        // Initialize with admin account
        $admin = [
            'id' => 1,
            'name' => 'Admin',
            'email' => 'admin@jourlestours.com',
            'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
            'created_at' => date('c')
        ];
        file_put_contents($file, json_encode([$admin], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
    $json = file_get_contents($file);
    $data = json_decode($json, true);
    if (!is_array($data)) $data = [];
    return $data;
}

function saveUsers(array $users) {
    $file = __DIR__ . DIRECTORY_SEPARATOR . 'users.json';
    file_put_contents($file, json_encode(array_values($users), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function findUserByEmail($email) {
    // Try MySQL first (if configured)
    $email = trim($email);
    if (defined('DB_HOST') && DB_HOST !== '') {
        $conn = getDb();
        if ($conn) {
            $sql = "SELECT id, name, email, password_hash, created_at FROM users WHERE email = ? LIMIT 1";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param('s', $email);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($row = $res->fetch_assoc()) {
                    $stmt->close();
                    // normalize keys to match file-based user format
                    return [
                        'id' => $row['id'],
                        'name' => $row['name'],
                        'email' => $row['email'],
                        'password_hash' => $row['password_hash'],
                        'created_at' => $row['created_at']
                    ];
                }
                $stmt->close();
            }
        }
    }

    // Fallback to file-based users
    $users = getUsers();
    foreach ($users as $user) {
        if (isset($user['email']) && strtolower($user['email']) === strtolower($email)) {
            return $user;
        }
    }
    return null;
}

function createUser($name, $email, $password) {
    $name = trim($name);
    $email = trim($email);
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Try MySQL if configured
    if (defined('DB_HOST') && DB_HOST !== '') {
        $conn = getDb();
        if ($conn) {
            createUsersTableIfNotExists($conn);
            $sql = "INSERT INTO users (name, email, password_hash, created_at) VALUES (?, ?, ?, NOW())";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param('sss', $name, $email, $password_hash);
                if ($stmt->execute()) {
                    $id = $stmt->insert_id;
                    $stmt->close();
                    return [
                        'id' => $id,
                        'name' => $name,
                        'email' => $email,
                        'password_hash' => $password_hash,
                        'created_at' => date('c')
                    ];
                }
                $stmt->close();
            }
        }
    }

    // Fallback to file-based storage
    $users = getUsers();
    // simple auto-increment id
    $ids = array_map(function($u){ return $u['id'] ?? 0; }, $users);
    $nextId = $ids ? max($ids) + 1 : 1;
    $user = [
        'id' => $nextId,
        'name' => $name,
        'email' => $email,
        'password_hash' => $password_hash,
        'created_at' => date('c')
    ];
    $users[] = $user;
    saveUsers($users);
    return $user;
}

function verifyUser($email, $password) {
    $email = trim($email);
    // Try MySQL first
    if (defined('DB_HOST') && DB_HOST !== '') {
        $conn = getDb();
        if ($conn) {
            $sql = "SELECT id, name, email, password_hash, created_at FROM users WHERE email = ? LIMIT 1";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param('s', $email);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($row = $res->fetch_assoc()) {
                    $stmt->close();
                    if (isset($row['password_hash']) && password_verify($password, $row['password_hash'])) {
                        return [
                            'id' => $row['id'],
                            'name' => $row['name'],
                            'email' => $row['email'],
                            'password_hash' => $row['password_hash'],
                            'created_at' => $row['created_at']
                        ];
                    }
                    return null;
                }
                $stmt->close();
            }
        }
    }

    // Fallback to file-based
    $user = findUserByEmail($email);
    if (!$user) return null;
    if (isset($user['password_hash']) && password_verify($password, $user['password_hash'])) {
        return $user;
    }
    return null;
}

/**
 * Get mysqli connection based on config.php. Returns mysqli or null.
 */
function getDb() {
    if (!defined('DB_HOST') || DB_HOST === '') return null;
    static $conn = null;
    if ($conn !== null) return $conn;

    // Use mysqli_init + options to set a short connect timeout to avoid long blocking
    $init = mysqli_init();
    if ($init === false) return null;
    // set connect timeout (seconds)
    mysqli_options($init, MYSQLI_OPT_CONNECT_TIMEOUT, 2);
    // attempt to connect
    if (!@mysqli_real_connect($init, DB_HOST, DB_USER, DB_PASS, DB_NAME)) {
        return null;
    }
    mysqli_set_charset($init, 'utf8mb4');
    $conn = $init;
    return $conn;
}

function createUsersTableIfNotExists($conn) {
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $conn->query($sql);
}

/**
 * Check if user is admin
 */
function isAdmin() {
    return isset($_SESSION['user']) && isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin';
}

/**
 * Admin check: redirect to home if not admin
 */
function requireAdmin() {
    if (!isAdmin()) {
        $_SESSION['flash_message'] = ['type' => 'error', 'text' => 'Bạn không có quyền truy cập.'];
        header('Location: ' . (strpos($_SERVER['PHP_SELF'], '/admin/') !== false ? '../' : '') . 'index.php');
        exit;
    }
}

/**
 * Comments / Reviews storage (file-based)
 */
function getComments() {
    $file = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'comments.json';
    if (!file_exists($file)) {
        if (!file_exists(dirname($file))) mkdir(dirname($file), 0755, true);
        file_put_contents($file, json_encode([]));
    }
    $json = file_get_contents($file);
    $data = json_decode($json, true);
    return is_array($data) ? $data : [];
}

function saveComments(array $comments) {
    $file = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'comments.json';
    if (!file_exists(dirname($file))) mkdir(dirname($file), 0755, true);
    file_put_contents($file, json_encode(array_values($comments), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function createComment($user_id, $user_email, $product_id, $content, $rating = null, $status = 'pending') {
    $comments = getComments();
    $ids = array_map(function($c){ return $c['id'] ?? 0; }, $comments);
    $nextId = $ids ? max($ids) + 1 : 1;
    $comment = [
        'id' => $nextId,
        'user_id' => $user_id,
        'user_email' => $user_email,
        'product_id' => $product_id,
        'content' => $content,
        'rating' => $rating,
        'status' => $status,
        'created_at' => date('c'),
        'updated_at' => date('c')
    ];
    $comments[] = $comment;
    saveComments($comments);
    return $comment;
}

function deleteComment($id) {
    $comments = getComments();
    $comments = array_filter($comments, function($c) use ($id) { return ($c['id'] ?? 0) != $id; });
    saveComments($comments);
}

/**
 * Get/Set categories from JSON file
 */
function getCategories() {
    $file = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'categories.json';
    if (!file_exists(dirname($file))) {
        mkdir(dirname($file), 0755, true);
    }
    if (!file_exists($file)) {
        // Default categories
        $defaults = [
            ['id' => 1, 'name' => 'BÁNH KEM', 'price_range' => '300000-500000', 'created_at' => date('c')],
            ['id' => 2, 'name' => 'MACARON', 'price_range' => '200000-400000', 'created_at' => date('c')],
            ['id' => 3, 'name' => 'MOUSSE & CAKE', 'price_range' => '250000-450000', 'created_at' => date('c')],
        ];
        file_put_contents($file, json_encode($defaults, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
    $json = file_get_contents($file);
    $data = json_decode($json, true);
    return is_array($data) ? $data : [];
}

function saveCategories(array $categories) {
    $file = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'categories.json';
    if (!file_exists(dirname($file))) {
        mkdir(dirname($file), 0755, true);
    }
    file_put_contents($file, json_encode(array_values($categories), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

/**
 * Get/Save orders from JSON file
 */
function getOrders() {
    $file = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'orders.json';
    if (!file_exists($file)) {
        file_put_contents($file, json_encode([]));
    }
    $json = file_get_contents($file);
    $data = json_decode($json, true);
    return is_array($data) ? $data : [];
}

function saveOrders(array $orders) {
    $file = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'orders.json';
    if (!file_exists(dirname($file))) {
        mkdir(dirname($file), 0755, true);
    }
    file_put_contents($file, json_encode(array_values($orders), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function createOrder($user_id, $user_email, $items, $total, $status = 'pending') {
    $orders = getOrders();
    $ids = array_map(function($o){ return $o['id'] ?? 0; }, $orders);
    $nextId = $ids ? max($ids) + 1 : 1;
    
    $order = [
        'id' => $nextId,
        'user_id' => $user_id,
        'user_email' => $user_email,
        'items' => $items,
        'total' => $total,
        'status' => $status,
        'created_at' => date('c'),
        'updated_at' => date('c')
    ];
    $orders[] = $order;
    saveOrders($orders);
    return $order;
}
