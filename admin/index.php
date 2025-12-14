<?php
session_start();
require_once __DIR__ . '/../functions.php';
requireAdmin();

$page_title = 'Dashboard';

// Get stats
$orders = getOrders();
$users = getUsers();
$categories = getCategories();

$order_count = count($orders);
$customer_count = count($users);
$category_count = count($categories);
$total_revenue = array_sum(array_map(function($o){ return $o['total'] ?? 0; }, $orders));

// Get recent orders
$recent_orders = array_reverse($orders);
$recent_orders = array_slice($recent_orders, 0, 5);

// Get order status counts
$pending = count(array_filter($orders, function($o){ return ($o['status'] ?? 'pending') === 'pending'; }));
$completed = count(array_filter($orders, function($o){ return ($o['status'] ?? 'pending') === 'completed'; }));
?>
<?php require_once __DIR__ . '/partials/header.php'; ?>

<div class="content-box">
    <h3>Tổng Quan</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 20px;">
        <div style="background: #e8f4f8; padding: 20px; border-radius: 8px; text-align: center; border-left: 4px solid #3498db;">
            <div style="font-size: 28px; font-weight: bold; color: #3498db;"><?php echo $order_count; ?></div>
            <div style="font-size: 14px; color: #555; margin-top: 5px;">Tổng Đơn Hàng</div>
        </div>
        <div style="background: #f0e8f4; padding: 20px; border-radius: 8px; text-align: center; border-left: 4px solid #9b59b6;">
            <div style="font-size: 28px; font-weight: bold; color: #9b59b6;"><?php echo $customer_count; ?></div>
            <div style="font-size: 14px; color: #555; margin-top: 5px;">Tổng Khách Hàng</div>
        </div>
        <div style="background: #fff3e0; padding: 20px; border-radius: 8px; text-align: center; border-left: 4px solid #f39c12;">
            <div style="font-size: 28px; font-weight: bold; color: #f39c12;"><?php echo $category_count; ?></div>
            <div style="font-size: 14px; color: #555; margin-top: 5px;">Danh Mục</div>
        </div>
        <div style="background: #e8f8f5; padding: 20px; border-radius: 8px; text-align: center; border-left: 4px solid #27ae60;">
            <div style="font-size: 28px; font-weight: bold; color: #27ae60;"><?php echo number_format($total_revenue, 0, ',', '.'); ?>₫</div>
            <div style="font-size: 14px; color: #555; margin-top: 5px;">Doanh Thu Tổng</div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
    <div class="content-box">
        <h3>Trạng Thái Đơn Hàng</h3>
        <table>
            <tr>
                <td>Đang Chờ Xử Lý</td>
                <td style="text-align: right; font-weight: bold; color: #f39c12;"><?php echo $pending; ?></td>
            </tr>
            <tr>
                <td>Hoàn Thành</td>
                <td style="text-align: right; font-weight: bold; color: #27ae60;"><?php echo $completed; ?></td>
            </tr>
        </table>
    </div>

    <div class="content-box">
        <h3>Các Thao Tác Nhanh</h3>
        <div style="display: flex; flex-direction: column; gap: 10px;">
            <a href="pages/categories.php" class="btn btn-primary">Quản Lý Danh Mục</a>
            <a href="pages/orders.php" class="btn btn-primary">Xem Đơn Hàng</a>
            <a href="pages/comments.php" class="btn btn-primary">Quản Lý Bình Luận</a>
            <a href="pages/customers.php" class="btn btn-primary">Quản Lý Khách Hàng</a>
        </div>
    </div>
</div>

<div class="content-box">
    <h3>Đơn Hàng Gần Nhất</h3>
    <?php if (empty($recent_orders)): ?>
        <p style="text-align: center; color: #999; margin-top: 15px;">Chưa có đơn hàng nào</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email Khách</th>
                    <th>Tổng Tiền</th>
                    <th>Trạng Thái</th>
                    <th>Ngày Tạo</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent_orders as $order): ?>
                <tr>
                    <td>#<?php echo $order['id']; ?></td>
                    <td><?php echo htmlspecialchars($order['user_email']); ?></td>
                    <td><?php echo number_format($order['total'], 0, ',', '.'); ?>₫</td>
                    <td>
                        <span style="padding: 5px 10px; border-radius: 3px; background: <?php echo ($order['status'] ?? 'pending') === 'completed' ? '#d4edda' : '#fff3cd'; ?>; color: <?php echo ($order['status'] ?? 'pending') === 'completed' ? '#155724' : '#856404'; ?>;">
                            <?php echo ($order['status'] ?? 'pending') === 'completed' ? 'Hoàn Thành' : 'Đang Chờ'; ?>
                        </span>
                    </td>
                    <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    <div style="margin-top: 15px;">
           <a href="pages/orders.php" class="btn btn-primary btn-small">Xem Tất Cả Đơn Hàng</a>
    </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
