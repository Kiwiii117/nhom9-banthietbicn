<?php
require_once "database.php";

// Đếm nhanh số liệu thống kê để hiển thị lên bảng tổng quan cho sinh động
$total_products = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$total_categories = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$total_brands = $pdo->query("SELECT COUNT(*) FROM brands")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang Tổng Quan - Quản Trị Laptop</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, sans-serif; }
        body { display: flex; background-color: #f4f6f9; color: #333; height: 100vh; overflow: hidden; }
        
        /* Sidebar Cố Định Bên Trái */
        .sidebar { width: 260px; background-color: #2c3e50; color: #fff; display: flex; flex-direction: column; box-shadow: 2px 0 5px rgba(0,0,0,0.1); }
        .sidebar-brand { padding: 25px 20px; font-size: 20px; font-weight: bold; background-color: #1a252f; text-align: center; border-bottom: 1px solid #34495e; letter-spacing: 0.5px; }
        .sidebar-menu { list-style: none; padding: 20px 0; flex: 1; }
        .sidebar-menu li a { display: block; padding: 15px 20px; color: #b8c7ce; text-decoration: none; font-size: 15px; transition: 0.2s; border-left: 4px solid transparent; }
        .sidebar-menu li a:hover, .sidebar-menu li a.active { background-color: #34495e; color: #fff; border-left-color: #3498db; }
        
        /* Phần Nội Dung Chính Bên Phải */
        .main-content { flex: 1; display: flex; flex-direction: column; overflow-y: auto; }
        .topbar { background: #fff; padding: 20px 30px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; }
        .topbar h1 { font-size: 22px; color: #2c3e50; }
        .dashboard-body { padding: 30px; }
        
        /* Khung Chào Mừng */
        .welcome-box { background: linear-gradient(135deg, #3498db, #2980b9); color: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(52, 152, 219, 0.2); margin-bottom: 30px; }
        .welcome-box h2 { margin-bottom: 10px; font-size: 26px; }
        .welcome-box p { font-size: 16px; opacity: 0.95; line-height: 1.6; }

        /* Các Thẻ Thống Kê (Cards) */
        .cards-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .card { background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-left: 5px solid #3498db; }
        .card.categories { border-left-color: #27ae60; }
        .card.brands { border-left-color: #e67e22; }
        .card-title { font-size: 14px; color: #7f8c8d; text-transform: uppercase; font-weight: bold; }
        .card-value { font-size: 32px; font-weight: bold; color: #2c3e50; margin-top: 10px; }
        .card-link { display: inline-block; margin-top: 15px; font-size: 13px; color: #3498db; text-decoration: none; font-weight: 600; }
        .card-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <!-- 1. SIDEBAR ĐIỀU HƯỚNG -->
    <div class="sidebar">
        <div class="sidebar-brand">💻 Laptop Admin</div>
        <ul class="sidebar-menu">
            <li><a href="index.php" class="active">🏠 Trang Tổng Quan</a></li>
            <li><a href="products.php">💻 Quản lý Sản Phẩm</a></li>
            <li><a href="brands.php">🏷️ Quản lý Hãng</a></li>
            <li><a href="categories.php">📁 Quản lý Danh Mục</a></li>
        </ul>
    </div>

    <!-- 2. KHUNG NỘI DUNG CHÍNH -->
    <div class="main-content">
        <div class="topbar">
            <h1>Hệ Thống Quản Trị Website Laptop</h1>
        </div>

        <div class="dashboard-body">
            <!-- Lời chào thân thiện -->
            <div class="welcome-box">
                <h2>Xin chào, your_username_here</h2>
                <p>Hệ thống cơ sở dữ liệu đã sẵn sàng. Chúc bạn làm việc vui vẻ!</p>
            </div>

            <div style="margin-bottom: 15px; font-size: 18px; font-weight: bold; color: #2c3e50;">📊 Thống kê nhanh dữ liệu hệ thống</div>

            <!-- Các ô hiển thị số lượng trực quan -->
            <div class="cards-grid">
                <div class="card products">
                    <div class="card-title">Tổng số sản phẩm</div>
                    <div class="card-value"><?= $total_products ?></div>
                    <a href="products.php" class="card-link">Quản lý sản phẩm &rarr;</a>
                </div>
                <div class="card categories">
                    <div class="card-title">Tổng số danh mục</div>
                    <div class="card-value"><?= $total_categories ?></div>
                    <a href="categories.php" class="card-link">Quản lý danh mục &rarr;</a>
                </div>
                <div class="card brands">
                    <div class="card-title">Tổng số hãng</div>
                    <div class="card-value"><?= $total_brands ?></div>
                    <a href="brands.php" class="card-link">Quản lý hãng &rarr;</a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>