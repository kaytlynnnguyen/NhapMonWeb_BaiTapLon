<?php
// Admin header - requires session and functions.php already loaded
if (!isset($_SESSION)) {
    session_start();
}
require_once __DIR__ . '/../../functions.php';
requireAdmin();
?>
<?php
// Compute admin base URL (handles both /admin and /admin/pages/* paths)
$curDir = dirname($_SERVER['PHP_SELF']);
$adminBase = preg_replace('#/pages$#', '', $curDir);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' - ' : ''; ?>Admin - Jour Les Tours</title>
    <link rel="stylesheet" href="../css/admin.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; color: #333; }
        .admin-container { display: flex; min-height: 100vh; }
        .admin-sidebar { width: 250px; background: #2c3e50; color: #fff; padding: 20px; position: fixed; height: 100vh; overflow-y: auto; }
        .admin-main { margin-left: 250px; flex: 1; padding: 20px; }
        .admin-header { background: #fff; padding: 20px; border-bottom: 1px solid #ddd; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; border-radius: 5px; }
        .admin-header h2 { font-size: 24px; color: #2c3e50; }
        .admin-header-right { display: flex; gap: 15px; align-items: center; }
        .admin-header-right a { color: #FF69B4; text-decoration: none; padding: 8px 12px; border-radius: 5px; border: 1px solid #FF69B4; transition: 0.3s; }
        .admin-header-right a:hover { background: #FF69B4; color: #fff; }
        .sidebar-logo { font-size: 18px; font-weight: bold; margin-bottom: 30px; text-align: center; padding: 10px; border-bottom: 1px solid #34495e; }
        .sidebar-menu { list-style: none; }
        .sidebar-menu li { margin: 15px 0; }
        .sidebar-menu a { color: #ecf0f1; text-decoration: none; display: block; padding: 12px; border-radius: 5px; transition: 0.3s; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: #FF69B4; color: #fff; }
        .content-box { background: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .btn { display: inline-block; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; font-size: 14px; transition: 0.3s; }
        .btn-primary { background: #FF69B4; color: #fff; }
        .btn-primary:hover { background: #FF1493; }
        .btn-danger { background: #e74c3c; color: #fff; }
        .btn-danger:hover { background: #c0392b; }
        .btn-success { background: #27ae60; color: #fff; }
        .btn-success:hover { background: #229954; }
        .btn-small { padding: 6px 10px; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table thead { background: #f8f9fa; }
        table th, table td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        table tr:hover { background: #f9f9f9; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 500; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-family: inherit; }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus { outline: none; border-color: #FF69B4; box-shadow: 0 0 5px rgba(255,105,180,0.3); }
        .flash { padding: 15px; border-radius: 5px; margin-bottom: 15px; }
        .flash.success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .flash.error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-logo">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="currentColor" style="vertical-align:middle;margin-right:8px"><path d="M3 13h2v8H3zm4-8h2v16H7zm4-2h2v18h-2zm4 4h2v14h-2zm4-2h2v16h-2z"/></svg>
                Jour Admin
            </div>
            <ul class="sidebar-menu">
                <li><a href="<?php echo $adminBase; ?>/index.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : ''; ?>">📊 Dashboard</a></li>
                <li><a href="<?php echo $adminBase; ?>/pages/categories.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'categories.php' ? 'active' : ''; ?>">📂 Danh Mục</a></li>
                <li><a href="<?php echo $adminBase; ?>/pages/orders.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'orders.php' ? 'active' : ''; ?>">📦 Đơn Hàng</a></li>
                <li><a href="<?php echo $adminBase; ?>/pages/comments.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'comments.php' ? 'active' : ''; ?>">💬 Bình Luận</a></li>
                <li><a href="<?php echo $adminBase; ?>/pages/customers.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'customers.php' ? 'active' : ''; ?>">👥 Khách Hàng</a></li>
                <li><a href="../../auth_actions.php?action=logout">🚪 Đăng Xuất</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <div class="admin-header">
                <h2><?php echo isset($page_title) ? htmlspecialchars($page_title) : 'Admin Dashboard'; ?></h2>
                <div class="admin-header-right">
                    <span>Xin chào, <?php echo htmlspecialchars($_SESSION['user']['name'] ?? 'Admin'); ?></span>
                    <a href="../../auth_actions.php?action=logout">Đăng xuất</a>
                </div>
            </div>
