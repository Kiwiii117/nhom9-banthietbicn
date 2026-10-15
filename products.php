<?php
require_once "database.php";

$action = $_GET['action'] ?? 'index';

// XỬ LÝ XÓA
if ($action === 'delete') {
    $id = $_GET['id'] ?? 0;
    if ($id > 0) {
        $stm = $pdo->prepare("DELETE FROM products WHERE id = :id");
        $stm->execute(['id' => $id]);
    }
    header("Location: products.php");
    exit();
}

// XỬ LÝ THÊM
if ($action === 'store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $category_id = $_POST['category_id'] ?? 0;
    $brand_id = $_POST['brand_id'] ?? 0;
    $price = $_POST['price'] ?? 0;
    $stock = $_POST['stock'] ?? 0;
    $cpu = $_POST['cpu'] ?? '';
    $ram = $_POST['ram'] ?? '';
    $storage = $_POST['storage'] ?? '';
    $description = $_POST['description'] ?? '';

    if (!empty($name) && $price > 0) {
        $sql = "INSERT INTO products (category_id, brand_id, name, price, stock, cpu, ram, storage, description) 
                VALUES (:cat, :brand, :name, :price, :stock, :cpu, :ram, :storage, :desc)";
        $stm = $pdo->prepare($sql);
        $stm->execute([
            'cat' => $category_id, 'brand' => $brand_id, 'name' => $name,
            'price' => $price, 'stock' => $stock, 'cpu' => $cpu,
            'ram' => $ram, 'storage' => $storage, 'desc' => $description
        ]);
    }
    header("Location: products.php");
    exit();
}

// XỬ LÝ SỬA
if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? 0;
    $name = $_POST['name'] ?? '';
    $category_id = $_POST['category_id'] ?? 0;
    $brand_id = $_POST['brand_id'] ?? 0;
    $price = $_POST['price'] ?? 0;
    $stock = $_POST['stock'] ?? 0;
    $cpu = $_POST['cpu'] ?? '';
    $ram = $_POST['ram'] ?? '';
    $storage = $_POST['storage'] ?? '';
    $description = $_POST['description'] ?? '';

    if ($id > 0 && !empty($name) && $price > 0) {
        $sql = "UPDATE products SET category_id = :cat, brand_id = :brand, name = :name, price = :price, 
                stock = :stock, cpu = :cpu, ram = :ram, storage = :storage, description = :desc WHERE id = :id";
        $stm = $pdo->prepare($sql);
        $stm->execute([
            'cat' => $category_id, 'brand' => $brand_id, 'name' => $name,
            'price' => $price, 'stock' => $stock, 'cpu' => $cpu,
            'ram' => $ram, 'storage' => $storage, 'desc' => $description, 'id' => $id
        ]);
    }
    header("Location: products.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Sản Phẩm - Laptop Admin</title>
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
        table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 14px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #f8f9fa; color: #2c3e50; }
        tr:hover { background-color: #fdfdfd; }
        .btn { padding: 6px 12px; text-decoration: none; border-radius: 4px; color: #fff; font-size: 14px; font-weight: 600; }
        .btn-add { background: #27ae60; display: inline-block; margin-bottom: 15px; padding: 10px 18px; }
        .btn-edit { background: #3498db; }
        .btn-delete { background: #e74c3c; }
        label { font-weight: 600; display: block; margin-top: 12px; color: #2c3e50; }
        input, select, textarea { width: 100%; padding: 10px; margin-top: 5px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        textarea { height: 80px; }
        button[type="submit"] { background: #27ae60; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 15px; font-weight: 600; margin-top: 20px; }
        .back-link { display: inline-block; margin-top: 20px; text-decoration: none; color: #3498db; font-weight: 600; }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">💻 Laptop Admin</div>
        <ul class="sidebar-menu">
            <li><a href="index.php">🏠 Trang Tổng Quan</a></li>
            <li><a href="products.php" class="active">💻 Quản lý Sản Phẩm</a></li>
            <li><a href="brands.php">🏷️ Quản lý Hãng</a></li>
            <li><a href="categories.php">📁 Quản lý Danh Mục</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="topbar">
            <h1>Quản Lý Sản Phẩm Laptop</h1>
            <span style="font-weight: 600; color: #555;">👋 Admin</span>
        </div>

        <div class="dashboard-body">
            <div class="content-box">
                <?php if ($action === 'create'): ?>
                    <?php
                    $categories = $pdo->query("SELECT * FROM categories")->fetchAll();
                    $brands = $pdo->query("SELECT * FROM brands")->fetchAll();
                    ?>
                    <h2>Thêm Mới Laptop 📝</h2>
                    <form action="products.php?action=store" method="POST">
                        <label>Tên Laptop:</label>
                        <input type="text" name="name" required placeholder="VD: Asus Zenbook 14 OLED">

                        <label>Hãng sản xuất:</label>
                        <select name="brand_id">
                            <?php foreach($brands as $b): ?>
                                <option value="<?= $b['id'] ?>"><?= $b['name'] ?></option>
                            <?php endforeach; ?>
                        </select>

                        <label>Danh mục:</label>
                        <select name="category_id">
                            <?php foreach($categories as $c): ?>
                                <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
                            <?php endforeach; ?>
                        </select>

                        <label>Giá bán (VNĐ):</label>
                        <input type="number" name="price" required min="0" step="1000">

                        <label>Số lượng tồn kho:</label>
                        <input type="number" name="stock" value="10" min="0">

                        <label>CPU:</label>
                        <input type="text" name="cpu" placeholder="VD: Intel Core i5-13500H">

                        <label>RAM:</label>
                        <input type="text" name="ram" placeholder="VD: 16GB LPDDR5">

                        <label>Ổ cứng (Storage):</label>
                        <input type="text" name="storage" placeholder="VD: 512GB SSD NVMe">

                        <label>Mô tả chi tiết:</label>
                        <textarea name="description" placeholder="Mô tả cấu hình, tính năng..."></textarea>

                        <button type="submit">Lưu sản phẩm</button>
                    </form>
                    <a href="products.php" class="back-link">⬅ Quay lại danh sách</a>

                <?php elseif ($action === 'edit'): ?>
                    <?php
                    $id = $_GET['id'] ?? 0;
                    $stm = $pdo->prepare("SELECT * FROM products WHERE id = :id");
                    $stm->execute(['id' => $id]);
                    $product = $stm->fetch();
                    if (!$product) { header("Location: products.php"); exit(); }

                    $categories = $pdo->query("SELECT * FROM categories")->fetchAll();
                    $brands = $pdo->query("SELECT * FROM brands")->fetchAll();
                    ?>
                    <h2>Sửa Thông Tin Laptop ✏️</h2>
                    <form action="products.php?action=update" method="POST">
                        <input type="hidden" name="id" value="<?= $product['id'] ?>">
                        
                        <label>Tên Laptop:</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

                        <label>Hãng sản xuất:</label>
                        <select name="brand_id">
                            <?php foreach($brands as $b): ?>
                                <option value="<?= $b['id'] ?>" <?= ($b['id'] == $product['brand_id']) ? 'selected' : '' ?>><?= $b['name'] ?></option>
                            <?php endforeach; ?>
                        </select>

                        <label>Danh mục:</label>
                        <select name="category_id">
                            <?php foreach($categories as $c): ?>
                                <option value="<?= $c['id'] ?>" <?= ($c['id'] == $product['category_id']) ? 'selected' : '' ?>><?= $c['name'] ?></option>
                            <?php endforeach; ?>
                        </select>

                        <label>Giá bán (VNĐ):</label>
                        <input type="number" name="price" value="<?= $product['price'] ?>" required min="0" step="1000">

                        <label>Số lượng tồn kho:</label>
                        <input type="number" name="stock" value="<?= $product['stock'] ?>" min="0">

                        <label>CPU:</label>
                        <input type="text" name="cpu" value="<?= htmlspecialchars($product['cpu'] ?? '') ?>">

                        <label>RAM:</label>
                        <input type="text" name="ram" value="<?= htmlspecialchars($product['ram'] ?? '') ?>">

                        <label>Ổ cứng (Storage):</label>
                        <input type="text" name="storage" value="<?= htmlspecialchars($product['storage'] ?? '') ?>">

                        <label>Mô tả chi tiết:</label>
                        <textarea name="description"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>

                        <button type="submit" style="background: #3498db;">Cập nhật sản phẩm</button>
                    </form>
                    <a href="products.php" class="back-link">⬅ Quay lại danh sách</a>

                <?php else: ?>
                    <h2>Danh sách sản phẩm laptop</h2>
                    <a href="products.php?action=create" class="btn btn-add">+ Thêm laptop mới</a>
                    <table>
                        <tr>
                            <th>ID</th>
                            <th>Tên máy</th>
                            <th>Hãng</th>
                            <th>Danh mục</th>
                            <th>Giá bán</th>
                            <th>Kho</th>
                            <th>Cấu hình</th>
                            <th>Thao tác</th>
                        </tr>
                        <?php
                        $sql = "SELECT products.*, categories.name AS cat_name, brands.name AS brand_name 
                                FROM products 
                                JOIN categories ON products.category_id = categories.id 
                                JOIN brands ON products.brand_id = brands.id 
                                ORDER BY products.id DESC";
                        $data = $pdo->query($sql)->fetchAll();
                        foreach($data as $row): 
                        ?>
                        <tr>
                            <td>#<?= $row['id'] ?></td>
                            <td><strong><?= htmlspecialchars($row['name']) ?></strong></td>
                            <td><?= htmlspecialchars($row['brand_name']) ?></td>
                            <td><?= htmlspecialchars($row['cat_name']) ?></td>
                            <td style="color: #c0392b; font-weight: bold;"><?= number_format($row['price'], 0, ',', '.') ?> đ</td>
                            <td><?= $row['stock'] ?></td>
                            <td><?= htmlspecialchars($row['cpu']) ?> / <?= htmlspecialchars($row['ram']) ?></td>
                            <td>
                                <a href="products.php?action=edit&id=<?= $row['id'] ?>" class="btn btn-edit">Sửa</a>
                                <a href="products.php?action=delete&id=<?= $row['id'] ?>" class="btn btn-delete" onclick="return confirm('Xóa sản phẩm này nhé?');">Xóa</a>
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