<?php
session_start();
require_once __DIR__ . '/../../functions.php';
requireAdmin();

$page_title = 'Quản Lý Đơn Hàng';

$flash = null;
$action = $_GET['action'] ?? $_POST['action'] ?? null;
$filter = $_GET['filter'] ?? 'all';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update_status') {
    $order_id = intval($_POST['id'] ?? 0);
    $status = trim($_POST['status'] ?? 'pending');
    
    $orders = getOrders();
    foreach ($orders as &$order) {
        if ($order['id'] == $order_id) {
            $order['status'] = $status;
            $order['updated_at'] = date('c');
            break;
        }
    }
    saveOrders($orders);
    $flash = ['type' => 'success', 'text' => 'Cập nhật trạng thái đơn hàng thành công'];
}

$orders = getOrders();
$orders = array_reverse($orders); // Mới nhất trước

// apply filter if requested
if ($filter !== 'all') {
    $orders = array_values(array_filter($orders, function($o) use ($filter) {
        return (($o['status'] ?? 'pending') === $filter);
    }));
}
?>
<?php require_once __DIR__ . '/../partials/header.php'; ?>

<?php if ($flash): ?>
    <div class="flash <?php echo $flash['type']; ?>">
        <?php echo htmlspecialchars($flash['text']); ?>
    </div>
<?php endif; ?>

<div class="content-box">
    <h3>Danh Sách Đơn Hàng</h3>
    <div style="margin-bottom:10px;">
        <a href="?filter=all" style="margin-right:8px;">Tất cả</a>
        <a href="?filter=pending" style="margin-right:8px;">Đang chờ</a>
        <a href="?filter=completed" style="margin-right:8px;">Hoàn thành</a>
        <a href="?filter=cancelled">Hủy</a>
    </div>
    <?php if (empty($orders)): ?>
        <p style="text-align: center; color: #999; margin-top: 15px;">Chưa có đơn hàng nào</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email Khách</th>
                    <th>Số Lượng Sản Phẩm</th>
                    <th>Tổng Tiền</th>
                    <th>Trạng Thái</th>
                    <th>Ngày Tạo</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td>#<?php echo $order['id']; ?></td>
                    <td><?php echo htmlspecialchars($order['user_email']); ?></td>
                    <td><?php echo count($order['items'] ?? []); ?></td>
                    <td><?php echo number_format($order['total'], 0, ',', '.'); ?>₫</td>
                    <td>
                        <?php $st = $order['status'] ?? 'pending'; ?>
                        <?php if ($st === 'pending'): ?>
                            <form method="post" style="display:inline-block; margin-right:6px;">
                                <input type="hidden" name="action" value="update_status">
                                <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" style="padding:6px 10px; background:#28a745; color:#fff; border:0; border-radius:3px;">Hoàn Thành</button>
                            </form>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="action" value="update_status">
                                <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
                                <input type="hidden" name="status" value="cancelled">
                                <button type="submit" style="padding:6px 10px; background:#dc3545; color:#fff; border:0; border-radius:3px;">Hủy</button>
                            </form>
                        <?php else: ?>
                            <span style="padding:6px 8px; border-radius:3px; background:#f0f0f0; display:inline-block;">
                                <?php echo ucfirst($st); ?>
                            </span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                    <td>
                        <button class="btn btn-primary btn-small" onclick="showDetails(<?php echo htmlspecialchars(json_encode($order)); ?>)">Chi Tiết</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<script>
function showDetails(order) {
    let itemsHtml = '<ul>';
    if (order.items && Array.isArray(order.items)) {
        order.items.forEach(item => {
            itemsHtml += `<li>Sản phẩm ${item.product_id}: ${item.quantity} x</li>`;
        });
    }
    itemsHtml += '</ul>';
    alert(`Đơn hàng #${order.id}\nEmail: ${order.user_email}\nTổng: ${order.total}₫\nTrạng thái: ${order.status || 'pending'}\nSản phẩm:\n${itemsHtml}`);
}
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
