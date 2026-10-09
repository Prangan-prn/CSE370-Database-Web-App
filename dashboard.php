<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>

<style>
*{box-sizing:border-box}

body{
    margin:0;
    font-family:Arial,sans-serif;
    background:linear-gradient(135deg,#e3f2fd,#f3e5f5);
    color:#333;
}

.container{
    max-width:900px;
    margin:50px auto;
    padding:20px;
}

.header{
    background:linear-gradient(135deg,#1565c0,#7b1fa2);
    color:white;
    padding:30px;
    border-radius:15px;
    text-align:center;
    box-shadow:0 5px 15px #0002;
}

.header h2{
    margin:0 0 10px;
}

.role{
    display:inline-block;
    background:#ffffff33;
    padding:7px 15px;
    border-radius:20px;
}

.menu{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:18px;
    margin-top:25px;
}

.card{
    background:white;
    padding:22px;
    border-radius:12px;
    text-decoration:none;
    color:#333;
    font-weight:bold;
    text-align:center;
    box-shadow:0 3px 10px #0002;
    transition:.2s;
}

.card:hover{
    transform:translateY(-4px);
    box-shadow:0 7px 18px #0003;
}

.admin{
    background:#fff3e0;
    border-left:5px solid #f57c00;
}

.logout{
    background:#ffebee;
    color:#c62828;
    border-left:5px solid #e53935;
}

h3{
    margin:25px 0 10px;
    color:#555;
}
</style>
</head>

<body>

<div class="container">

    <div class="header">
        <h2>
            Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?>!
        </h2>

        <span class="role">
            Role: <?= htmlspecialchars($_SESSION['user_type']) ?>
        </span>
    </div>

    <!-- Travel Section -->
    <h3>Travel</h3>

    <div class="menu">

        <a class="card" href="destinations.php">
            🌍 Explore Destinations
        </a>

        <a class="card" href="gallery.php">
            📸 Travel Gallery
        </a>

        <a class="card" href="guides.php">
            🧭 Tour Guides
        </a>

        <a class="card" href="create_plan.php">
            📝 Create Travel Plan
        </a>

        <a class="card" href="my_plans.php">
            🗺️ My Travel Plans
        </a>

        <a class="card" href="preferences.php">
            ⭐ Travel Preferences
        </a>

        <!-- My Postcards -->
        <a class="card" href="postcard.php">
            💌 My Postcards
        </a>

        <a class="card" href="support.php">
            🎧 Support Center
        </a>

    </div>


    <!-- Admin Section -->
    <?php if ($_SESSION['user_type'] === 'admin'): ?>

        <h3>Admin</h3>

        <div class="menu">

            <a class="card admin" href="admin.php">
                ⚙️ Admin Dashboard
            </a>

            <a class="card admin" href="admin_destinations.php">
                🏙️ Manage Destinations
            </a>

            <a class="card admin" href="admin_guides.php">
                🧑‍✈️ Manage Tour Guides
            </a>

            <a class="card admin" href="admin_support.php">
                🎫 Manage Support Tickets
            </a>

        </div>

    <?php endif; ?>


    <!-- Logout -->
    <div class="menu">

        <a class="card logout" href="logout.php">
            🚪 Logout
        </a>

    </div>

</div>

</body>
</html>