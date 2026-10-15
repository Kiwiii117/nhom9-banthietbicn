<?php
require_once "database.php";

$action = $_GET['action'] ?? 'index';

if ($action === 'delete') {
    $id = $_GET['id'] ?? 0;
    if ($id > 0) {
        $stm = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $stm->execute(['id' => $id]);
    }
    header("Location: users.php");
    exit();
}

if ($action === 'store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $role = (int)($_POST['role'] ?? 0);

    if (!empty($fullname) && !empty($email) && !empty($password)) {
        $sql = "INSERT INTO users (fullname, email, password, phone, address, role) 
                VALUES (:full, :email, :pass, :phone, :address, :role)";
        $stm = $pdo->prepare($sql);
        $stm->execute([
            'full' => $fullname,
            'email' => $email,
            'pass' => $password,
            'phone' => $phone,
            'address' => $address,
            'role' => $role
        ]);
    }
    header("Location: users.php");
    exit();
}

if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? 0;
    $fullname = $_POST['fullname'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $role = (int)($_POST['role'] ?? 0);
    $password = $_POST['password'] ?? '';

    if ($id > 0 && !empty($fullname) && !empty($email)) {
        if (!empty($password)) {
            $sql = "UPDATE users SET fullname = :full, email = :email, phone = :phone, address = :address, role = :role, password = :pass WHERE id = :id";
            $stm = $pdo->prepare($sql);
            $stm->execute(['full' => $fullname, 'email' => $email, 'phone' => $phone, 'address' => $address, 'role' => $role, 'pass' => $password, 'id' => $id]);
        } else {
            $sql = "UPDATE users SET fullname = :full, email = :email, phone = :phone, address = :address, role = :role WHERE id = :id";
            $stm = $pdo->prepare($sql);
            $stm->execute(['full' => $fullname, 'email' => $email, 'phone' => $phone, 'address' => $address, 'role' => $role, 'id' => $id]);
        }
    }
    header("Location: users.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Người Dùng</title>
    <style>
       * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, sans-serif; }
        body { display: flex; background-color: #f4f6f9; color: #333; height: 100vh; overflow: hidden; }
        .sidebar { width: 260px; background-color: #2c3e50; color: #fff; display: flex; flex-direction: column; box-shadow: 2px 0 5px rgba(0,0,0,0.1); }
        .sidebar-brand { padding: 25px 20px; font-size: 20px; font-weight: bold; background-color: #1a252f; text-align: center; border-bottom: 1px solid #34495e; }
        .sidebar-menu { list-style: none; padding: 20px 0; flex: 1; }
        .sidebar-menu li a { display: block; padding: 15px 20px; color: #b8c7ce; text-decoration: none; font-size: 15px; transition: 0.2s; border-left: 4px solid transparent; }
        .sidebar-menu li a:hover, .sidebar-menu li a.active { background-color: #34495e; color: #fff; border-left-color: #3498db; }
        .main-content { flex: 1; display: flex; flex-direction: column; overflow-y: auto; }
        .topbar { background: #fff; padding: 20px 30px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; }
        .topbar h1 { font-size: 22px; color: #2c3e50; }
        .dashboard-body { padding: 30px; }
        .content-box { background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        h2 { color: #2c3e50; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 14px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #f8f9fa; color: #2c3e50; }
        .btn { padding: 6px 12px; text-decoration: none; border-radius: 4px; color: #fff; font-size: 14px; font-weight: 600; }
        .btn-add { background: #27ae60; display: inline-block; margin-bottom: 15px; padding: 10px 18px; }
        .btn-edit { background: #3498db; }
        .btn-delete { background: #e74c3c; }
        label { font-weight: 600; display: block; margin-top: 12px; color: #2c3e50; }
        input, select { width: 100%; padding: 10px; margin-top: 5px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        button[type="submit"] { background: #27ae60; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 15px; font-weight: 600; margin-top: 20px; }
        .back-link { display: inline-block; margin-top: 20px; text-decoration: none; color: #3498db; font-weight: 600; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; color: white; }
        .badge-admin { background: #e74c3c; }
        .badge-user { background: #2ecc71; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-brand"></div>
        <ul class="sidebar-menu">
            <li><a href="index.php">🏠 Trang Tổng Quan</a></li>
            <li><a href="products.php">💻 Quản lý Sản Phẩm</a></li>
            <li><a href="brands.php">🏷️ Quản lý Hãng</a></li>
            <li><a href="categories.php">📁 Quản lý Danh Mục</a></li>
            <li><a href="users.php" class="active">👤 Quản lý Người Dùng</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="topbar">
            <h1>Quản Lý Người Dùng</h1>
            <span style="font-weight: 600; color: #555;"></span>
        </div>

        <div class="dashboard-body">
            <div class="content-box">
                <?php if ($action === 'create'): ?>
                    <h2>Thêm Người Dùng Mới</h2>
                    <form action="users.php?action=store" method="POST">
                        <label>Họ và tên:</label>
                        <input type="text" name="fullname" required">

                        <label>Email (Được dùng làm tài khoản đăng nhập):</label>
                        <input type="email" name="email" required>

                        <label>Mật khẩu:</label>
                        <input type="password" name="password" required>

                        <label>Số điện thoại:</label>
                        <input type="text" name="phone">

                        <label>Địa chỉ:</label>
                        <input type="text" name="address">

                        <label>Vai trò:</label>
                        <select name="role">
                            <option value="0">Khách hàng</option>
                            <option value="1">Quản trị viên</option>
                        </select>

                        <button type="submit">Lưu người dùng</button>
                    </form>
                    <a href="users.php" class="back-link">⬅ Quay lại danh sách</a>
                <?php elseif ($action === 'edit'): ?>
                    <?php
                    $id = $_GET['id'] ?? 0;
                    $stm = $pdo->prepare("SELECT * FROM users WHERE id = :id");
                    $stm->execute(['id' => $id]);
                    $u = $stm->fetch();
                    if (!$u) { header("Location: users.php"); exit(); }
                    ?>
                    <h2>Sửa Người Dùng</h2>
                    <form action="users.php?action=update" method="POST">
                        <input type="hidden" name="id" value="<?= $u['id'] ?>">

                        <label>Họ và tên:</label>
                        <input type="text" name="fullname" value="<?= htmlspecialchars($u['fullname'] ?? '') ?>" required>

                        <label>Email:</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($u['email'] ?? '') ?>" required>

                        <label>Mật khẩu mới (Để trống nếu giữ nguyên):</label>
                        <input type="password" name="password" placeholder="Bỏ trống nếu không đổi">

                        <label>Số điện thoại:</label>
                        <input type="text" name="phone" value="<?= htmlspecialchars($u['phone'] ?? '') ?>">

                        <label>Địa chỉ:</label>
                        <input type="text" name="address" value="<?= htmlspecialchars($u['address'] ?? '') ?>">

                        <label>Vai trò (Role):</label>
                        <select name="role">
                            <option value="0" <?= ((int)$u['role'] === 0) ? 'selected' : '' ?>>Khách hàng</option>
                            <option value="1" <?= ((int)$u['role'] === 1) ? 'selected' : '' ?>>Quản trị viên</option>
                        </select>

                        <button type="submit" style="background: #3498db;">Cập nhật tài khoản</button>
                    </form>
                    <a href="users.php" class="back-link">⬅ Quay lại danh sách</a>
                <?php else: ?>
                    <h2>Danh sách người dùng hệ thống</h2>
                    <a href="users.php?action=create" class="btn btn-add">+ Thêm người dùng mới</a>
                    <table>
                        <tr>
                            <th>ID</th>
                            <th>Họ và tên</th>
                            <th>Email</th>
                            <th>Số điện thoại</th>
                            <th>Địa chỉ</th>
                            <th>Vai trò</th>
                            <th>Thao tác</th>
                        </tr>
                        <?php
                        $data = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();
                        foreach($data as $row): 
                        ?>
                        <tr>
                            <td>#<?= $row['id'] ?></td>
                            <td><strong><?= htmlspecialchars($row['fullname'] ?? '') ?></strong></td>
                            <td><?= htmlspecialchars($row['email'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['phone'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['address'] ?? '') ?></td>
                            <td>
                                <?php if ((int)$row['role'] === 1): ?>
                                    <span class="badge badge-admin">Quản trị viên</span>
                                <?php else: ?>
                                    <span class="badge badge-user">Khách hàng</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="users.php?action=edit&id=<?= $row['id'] ?>" class="btn btn-edit">Sửa</a>
                                <a href="users.php?action=delete&id=<?= $row['id'] ?>" class="btn btn-delete" onclick="return confirm('Xóa người dùng này nhé?');">Xóa</a>
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