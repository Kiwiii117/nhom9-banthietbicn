<?php
require_once "database.php";

$action = $_GET['action'] ?? 'index';

// XỬ LÝ XÓA
if ($action === 'delete') {
    $id = $_GET['id'] ?? 0;
    if ($id > 0) {
        $stm = $pdo->prepare("DELETE FROM categories WHERE id = :id");
        $stm->execute(['id' => $id]);
    }
    header("Location: categories.php");
    exit();
}

// XỬ LÝ THÊM
if ($action === 'store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    if (!empty($name)) {
        $stm = $pdo->prepare("INSERT INTO categories (name) VALUES (:name)");
        $stm->execute(['name' => $name]);
    }
    header("Location: categories.php");
    exit();
}

// XỬ LÝ SỬA
if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? 0;
    $name = $_POST['name'] ?? '';
    if ($id > 0 && !empty($name)) {
        $stm = $pdo->prepare("UPDATE categories SET name = :name WHERE id = :id");
        $stm->execute(['name' => $name, 'id' => $id]);
    }
    header("Location: categories.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Danh Mục - Laptop Admin</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, sans-serif; }
        body { display: flex; background-color: #f4f6f9; color: #333; height: 100vh; overflow: hidden; }
        
        /* Sidebar */
        .sidebar { width: 260px; background-color: #2c3e50; color: #fff; display: flex; flex-direction: column; box-shadow: 2px 0 5px rgba(0,0,0,0.1); }
        .sidebar-brand { padding: 25px 20px; font-size: 20px; font-weight: bold; background-color: #1a252f; text-align: center; border-bottom: 1px solid #34495e; }
        .sidebar-menu { list-style: none; padding: 20px 0; flex: 1; }
        .sidebar-menu li a { display: block; padding: 15px 20px; color: #b8c7ce; text-decoration: none; font-size: 15px; transition: 0.2s; border-left: 4px solid transparent; }
        .sidebar-menu li a:hover, .sidebar-menu li a.active { background-color: #34495e; color: #fff; border-left-color: #3498db; }
        
        /* Main Content */
        .main-content { flex: 1; display: flex; flex-direction: column; overflow-y: auto; }
        .topbar { background: #fff; padding: 20px 30px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; }
        .topbar h1 { font-size: 22px; color: #2c3e50; }
        .dashboard-body { padding: 30px; }
        
        /* Table & Form Styling */
        .content-box { background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        h2 { color: #2c3e50; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #f8f9fa; color: #2c3e50; }
        tr:hover { background-color: #fdfdfd; }
        .btn { padding: 6px 12px; text-decoration: none; border-radius: 4px; color: #fff; font-size: 14px; font-weight: 600; }
        .btn-add { background: #27ae60; display: inline-block; margin-bottom: 15px; padding: 10px 18px; }
        .btn-edit { background: #3498db; }
        .btn-delete { background: #e74c3c; }
        input[type="text"] { width: 100%; padding: 10px; margin: 10px 0 20px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button[type="submit"] { background: #27ae60; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 15px; font-weight: 600; }
        .back-link { display: inline-block; margin-top: 15px; text-decoration: none; color: #3498db; font-weight: 600; }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">💻 Laptop Admin</div>
        <ul class="sidebar-menu">
            <li><a href="index.php">🏠 Trang Tổng Quan</a></li>
            <li><a href="products.php">💻 Quản lý Sản Phẩm</a></li>
            <li><a href="brands.php">🏷️ Quản lý Hãng</a></li>
            <li><a href="categories.php" class="active">📁 Quản lý Danh Mục</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="topbar">
            <h1>Quản Lý Danh Mục</h1>
            <span style="font-weight: 600; color: #555;">👋 Admin</span>
        </div>

        <div class="dashboard-body">
            <div class="content-box">
                <?php if ($action === 'create'): ?>
                    <h2>Thêm Danh Mục Mới 📝</h2>
                    <form action="categories.php?action=store" method="POST">
                        <label>Tên danh mục:</label>
                        <input type="text" name="name" required placeholder="VD: Laptop Gaming">
                        <button type="submit">Lưu lại</button>
                    </form>
                    <a href="categories.php" class="back-link">⬅ Quay lại danh sách</a>

                <?php elseif ($action === 'edit'): ?>
                    <?php
                    $id = $_GET['id'] ?? 0;
                    $stm = $pdo->prepare("SELECT * FROM categories WHERE id = :id");
                    $stm->execute(['id' => $id]);
                    $cat = $stm->fetch();
                    ?>
                    <h2>Sửa Danh Mục ✏️</h2>
                    <form action="categories.php?action=update" method="POST">
                        <input type="hidden" name="id" value="<?= $cat['id'] ?? 0 ?>">
                        <label>Tên danh mục:</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($cat['name'] ?? '') ?>" required>
                        <button type="submit" style="background: #3498db;">Cập nhật</button>
                    </form>
                    <a href="categories.php" class="back-link">⬅ Quay lại danh sách</a>

                <?php else: ?>
                    <h2>Danh sách danh mục sản phẩm</h2>
                    <a href="categories.php?action=create" class="btn btn-add">+ Thêm danh mục mới</a>
                    <table>
                        <tr>
                            <th width="15%">ID</th>
                            <th width="55%">Tên danh mục</th>
                            <th width="30%">Thao tác</th>
                        </tr>
                        <?php 
                        $data = $pdo->query("SELECT * FROM categories ORDER BY id DESC")->fetchAll();
                        foreach($data as $row): 
                        ?>
                        <tr>
                            <td>#<?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td>
                                <a href="categories.php?action=edit&id=<?= $row['id'] ?>" class="btn btn-edit">Sửa</a>
                                <a href="categories.php?action=delete&id=<?= $row['id'] ?>" class="btn btn-delete" onclick="return confirm('Xóa danh mục này nhé?');">Xóa</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

</body>
</html>