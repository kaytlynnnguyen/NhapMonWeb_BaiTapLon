<?php
session_start();
require_once __DIR__ . '/../../functions.php';
requireAdmin();

$page_title = 'Quản Lý Danh Mục';

// Handle actions
$action = $_GET['action'] ?? $_POST['action'] ?? null;
$flash = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categories = getCategories();
    
    if ($action === 'add') {
        $name = trim($_POST['name'] ?? '');
        $price_range = trim($_POST['price_range'] ?? '');
        
        if (!$name) {
            $flash = ['type' => 'error', 'text' => 'Tên danh mục không được để trống'];
        } else {
            $ids = array_map(function($c){ return $c['id'] ?? 0; }, $categories);
            $nextId = $ids ? max($ids) + 1 : 1;
            
            $categories[] = [
                'id' => $nextId,
                'name' => $name,
                'price_range' => $price_range,
                'created_at' => date('c')
            ];
            saveCategories($categories);
            $flash = ['type' => 'success', 'text' => 'Thêm danh mục thành công'];
        }
    } elseif ($action === 'edit') {
        $id = intval($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $price_range = trim($_POST['price_range'] ?? '');
        
        if (!$name) {
            $flash = ['type' => 'error', 'text' => 'Tên danh mục không được để trống'];
        } else {
            foreach ($categories as &$cat) {
                if ($cat['id'] == $id) {
                    $cat['name'] = $name;
                    $cat['price_range'] = $price_range;
                    $cat['updated_at'] = date('c');
                    break;
                }
            }
            saveCategories($categories);
            $flash = ['type' => 'success', 'text' => 'Cập nhật danh mục thành công'];
        }
    } elseif ($action === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        $categories = array_filter($categories, function($c){ return $c['id'] != $id; });
        saveCategories($categories);
        $flash = ['type' => 'success', 'text' => 'Xóa danh mục thành công'];
    }
}

// Get all categories
$categories = getCategories();
$edit_id = intval($_GET['edit'] ?? 0);
$edit_cat = null;
if ($edit_id > 0) {
    foreach ($categories as $cat) {
        if ($cat['id'] == $edit_id) {
            $edit_cat = $cat;
            break;
        }
    }
}
?>
<?php require_once __DIR__ . '/../partials/header.php'; ?>

<?php if ($flash): ?>
    <div class="flash <?php echo $flash['type']; ?>">
        <?php echo htmlspecialchars($flash['text']); ?>
    </div>
<?php endif; ?>

<div class="content-box">
    <h3><?php echo $edit_cat ? 'Chỉnh Sửa Danh Mục' : 'Thêm Danh Mục Mới'; ?></h3>
    <form method="post">
        <input type="hidden" name="action" value="<?php echo $edit_cat ? 'edit' : 'add'; ?>">
        <?php if ($edit_cat): ?>
            <input type="hidden" name="id" value="<?php echo $edit_cat['id']; ?>">
        <?php endif; ?>
        
        <div class="form-group">
            <label>Tên Danh Mục</label>
            <input type="text" name="name" required value="<?php echo $edit_cat ? htmlspecialchars($edit_cat['name']) : ''; ?>">
        </div>
        
        <div class="form-group">
            <label>Khoảng Giá (VD: 300000-500000)</label>
            <input type="text" name="price_range" value="<?php echo $edit_cat ? htmlspecialchars($edit_cat['price_range']) : ''; ?>">
        </div>
        
        <button type="submit" class="btn btn-success"><?php echo $edit_cat ? 'Cập Nhật' : 'Thêm'; ?></button>
        <?php if ($edit_cat): ?>
            <a href="categories.php" class="btn btn-primary">Hủy</a>
        <?php endif; ?>
    </form>
</div>

<div class="content-box">
    <h3>Danh Sách Danh Mục</h3>
    <?php if (empty($categories)): ?>
        <p style="text-align: center; color: #999; margin-top: 15px;">Chưa có danh mục nào</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên Danh Mục</th>
                    <th>Khoảng Giá</th>
                    <th>Ngày Tạo</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $cat): ?>
                <tr>
                    <td><?php echo $cat['id']; ?></td>
                    <td><?php echo htmlspecialchars($cat['name']); ?></td>
                    <td><?php echo htmlspecialchars($cat['price_range']); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($cat['created_at'])); ?></td>
                    <td>
                        <a href="?edit=<?php echo $cat['id']; ?>" class="btn btn-primary btn-small">Sửa</a>
                        <form method="post" style="display: inline;" onsubmit="return confirm('Bạn chắc chắn?')">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $cat['id']; ?>">
                            <button type="submit" class="btn btn-danger btn-small">Xóa</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
