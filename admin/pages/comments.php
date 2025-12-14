<?php
session_start();
require_once __DIR__ . '/../../functions.php';
requireAdmin();

$page_title = 'Quản Lý Bình Luận';
$flash = null;
$action = $_GET['action'] ?? $_POST['action'] ?? null;
$filter = $_GET['filter'] ?? 'all';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'update_status') {
        $id = intval($_POST['id'] ?? 0);
        $status = trim($_POST['status'] ?? 'pending');
        $comments = getComments();
        foreach ($comments as &$c) {
            if ($c['id'] == $id) {
                $c['status'] = $status;
                $c['updated_at'] = date('c');
                break;
            }
        }
        saveComments($comments);
        $flash = ['type' => 'success', 'text' => 'Cập nhật trạng thái bình luận thành công'];
    } elseif ($action === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        deleteComment($id);
        $flash = ['type' => 'success', 'text' => 'Xóa bình luận thành công'];
    }
}

$comments = getComments();
$comments = array_reverse($comments);
if ($filter !== 'all') {
    $comments = array_values(array_filter($comments, function($c) use ($filter) { return (($c['status'] ?? 'pending') === $filter); }));
}
?>
<?php require_once __DIR__ . '/../partials/header.php'; ?>

<?php if ($flash): ?>
    <div class="flash <?php echo $flash['type']; ?>"><?php echo htmlspecialchars($flash['text']); ?></div>
<?php endif; ?>

<div class="content-box">
    <h3>Quản Lý Bình Luận / Đánh Giá</h3>
    <div style="margin-bottom:10px;">
        <a href="?filter=all" style="margin-right:8px;">Tất cả</a>
        <a href="?filter=pending" style="margin-right:8px;">Chờ duyệt</a>
        <a href="?filter=approved" style="margin-right:8px;">Đã duyệt</a>
        <a href="?filter=spam">Spam</a>
    </div>

    <?php if (empty($comments)): ?>
        <p style="text-align: center; color: #999; margin-top: 15px;">Chưa có bình luận nào</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Sản phẩm</th>
                    <th>Rating</th>
                    <th>Nội dung</th>
                    <th>Trạng Thái</th>
                    <th>Ngày</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($comments as $c): ?>
                <tr>
                    <td>#<?php echo $c['id']; ?></td>
                    <td><?php echo htmlspecialchars($c['user_email'] ?? ''); ?></td>
                    <td>
                        <?php $prod = null; if (!empty($c['product_id'])) { $prod = getProductById($c['product_id']); } ?>
                        <?php if ($prod): ?>
                            <a href="<?php echo $adminBase ?? '../'; ?>/../product.php?id=<?php echo $prod['id']; ?>" target="_blank"><?php echo htmlspecialchars($prod['name']); ?></a>
                        <?php else: ?>
                            <?php echo htmlspecialchars($c['product_id'] ?? ''); ?>
                        <?php endif; ?>
                    </td>
                    <td><?php echo isset($c['rating']) ? intval($c['rating']) : '-'; ?></td>
                    <td><?php echo nl2br(htmlspecialchars($c['content'])); ?></td>
                    <td><?php echo htmlspecialchars($c['status'] ?? 'pending'); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($c['created_at'])); ?></td>
                    <td>
                        <?php $st = $c['status'] ?? 'pending'; ?>
                        <?php if ($st === 'pending'): ?>
                            <form method="post" style="display:inline-block; margin-right:6px;">
                                <input type="hidden" name="action" value="update_status">
                                <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="btn btn-success btn-small">Duyệt</button>
                            </form>
                            <form method="post" style="display:inline-block; margin-right:6px;">
                                <input type="hidden" name="action" value="update_status">
                                <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
                                <input type="hidden" name="status" value="spam">
                                <button type="submit" class="btn btn-danger btn-small">Spam</button>
                            </form>
                            <form method="post" style="display:inline-block;" onsubmit="return confirm('Bạn chắc chắn muốn xóa?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-small">Xóa</button>
                            </form>
                        <?php else: ?>
                            <form method="post" style="display:inline-block; margin-right:6px;">
                                <input type="hidden" name="action" value="update_status">
                                <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="btn btn-primary btn-small">Đã duyệt</button>
                            </form>
                            <form method="post" style="display:inline-block;" onsubmit="return confirm('Bạn chắc chắn muốn xóa?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-small">Xóa</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
