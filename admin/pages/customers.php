<?php
session_start();
require_once __DIR__ . '/../../functions.php';
requireAdmin();

$page_title = 'Quản Lý Khách Hàng';

$flash = null;
$action = $_GET['action'] ?? $_POST['action'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update_status') {
    $user_id = intval($_POST['id'] ?? 0);
    $status = trim($_POST['status'] ?? 'active');
    
    $users = getUsers();
    foreach ($users as &$user) {
        if ($user['id'] == $user_id) {
            $user['status'] = $status;
            $user['updated_at'] = date('c');
            break;
        }
    }
    saveUsers($users);
    $flash = ['type' => 'success', 'text' => 'Cập nhật trạng thái khách hàng thành công'];
}

$users = getUsers();
?>
<?php require_once __DIR__ . '/../partials/header.php'; ?>

<?php if ($flash): ?>
    <div class="flash <?php echo $flash['type']; ?>">
        <?php echo htmlspecialchars($flash['text']); ?>
    </div>
<?php endif; ?>

<div class="content-box">
    <h3>Danh Sách Khách Hàng</h3>
    <?php if (empty($users)): ?>
        <p style="text-align: center; color: #999; margin-top: 15px;">Chưa có khách hàng nào</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>Ngày Tạo</th>
                    <th>Trạng Thái</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                    <td>
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="action" value="update_status">
                            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                            <select name="status" onchange="this.form.submit()" style="padding: 6px; border-radius: 3px; border: 1px solid #ddd;">
                                <option value="active" <?php echo ($user['status'] ?? 'active') === 'active' ? 'selected' : ''; ?>>Hoạt Động</option>
                                <option value="inactive" <?php echo ($user['status'] ?? 'active') === 'inactive' ? 'selected' : ''; ?>>Không Hoạt Động</option>
                                <option value="banned" <?php echo ($user['status'] ?? 'active') === 'banned' ? 'selected' : ''; ?>>Cấm</option>
                            </select>
                        </form>
                    </td>
                    <td>
                        <button class="btn btn-primary btn-small" onclick="showDetails(<?php echo htmlspecialchars(json_encode($user)); ?>)">Chi Tiết</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<script>
function showDetails(user) {
    alert(`Khách hàng #${user.id}\nTên: ${user.name}\nEmail: ${user.email}\nNgày Tạo: ${new Date(user.created_at).toLocaleDateString('vi-VN')}\nTrạng thái: ${user.status || 'active'}`);
}
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
