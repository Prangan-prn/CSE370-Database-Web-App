<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
if ($_SESSION['user_type'] !== 'admin') die("Access denied.");
include 'db.php';

$users=$conn->query("SELECT COUNT(*) total FROM user")->fetch_assoc()['total'];
$destinations=$conn->query("SELECT COUNT(*) total FROM destination")->fetch_assoc()['total'];
$guides=$conn->query("SELECT COUNT(*) total FROM tour_guide")->fetch_assoc()['total'];
$plans=$conn->query("SELECT COUNT(*) total FROM travel_plan")->fetch_assoc()['total'];
$images=$conn->query("SELECT COUNT(*) total FROM gallery_media")->fetch_assoc()['total'];
$tickets=$conn->query("SELECT COUNT(*) total FROM support_ticket WHERE Status='Open'")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>
<style>
*{box-sizing:border-box}body{margin:0;font-family:Arial,sans-serif;background:linear-gradient(135deg,#eef2ff,#f0fdf4);color:#1e293b}
.container{max-width:1000px;margin:40px auto;padding:20px}
.header{background:linear-gradient(135deg,#4f46e5,#7c3aed);color:white;padding:25px;border-radius:18px;text-align:center;box-shadow:0 8px 20px #0002}
.header h2{margin:0 0 8px}.header a{color:white;text-decoration:none}
.stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:18px;margin:25px 0}
.card{padding:22px;border-radius:15px;color:white;text-align:center;box-shadow:0 6px 15px #0002}
.card h3{margin:0;font-size:30px}.card p{margin:8px 0 0}
.c1{background:#3b82f6}.c2{background:#10b981}.c3{background:#f59e0b}
.c4{background:#8b5cf6}.c5{background:#ec4899}.c6{background:#ef4444}
.controls{background:white;padding:25px;border-radius:18px;box-shadow:0 5px 15px #0001}
.controls h3{margin-top:0}
.links{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:15px}
.links a{padding:16px;background:#f1f5f9;color:#4338ca;text-decoration:none;border-radius:10px;font-weight:bold;text-align:center}
.links a:hover{background:#e0e7ff}
</style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2>⚙️ Admin Dashboard</h2>
        <p>Manage your Scenic tourism system</p>
        <a href="dashboard.php">← Back to Dashboard</a>
    </div>

    <h3>📊 System Statistics</h3>
    <div class="stats">
        <div class="card c1"><h3><?= $users ?></h3><p>Users</p></div>
        <div class="card c2"><h3><?= $destinations ?></h3><p>Destinations</p></div>
        <div class="card c3"><h3><?= $guides ?></h3><p>Tour Guides</p></div>
        <div class="card c4"><h3><?= $plans ?></h3><p>Travel Plans</p></div>
        <div class="card c5"><h3><?= $images ?></h3><p>Gallery Images</p></div>
        <div class="card c6"><h3><?= $tickets ?></h3><p>Open Tickets</p></div>
    </div>

    <div class="controls">
        <h3>🛠️ Admin Controls</h3>
        <div class="links">
            <a href="admin_destinations.php">🌍 Manage Destinations</a>
            <a href="admin_seasonal.php">❄️ Manage Seasonal Advisory</a>
            <a href="admin_guides.php">👨‍💼 Manage Tour Guides</a>
            <a href="admin_support.php">🎫 Manage Support Tickets</a>
        </div>
    </div>
</div>

</body>
</html>