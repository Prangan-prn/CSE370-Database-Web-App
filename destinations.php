<?php
session_start();
if(!isset($_SESSION['user_id'])){header("Location: login.php");exit();}
include 'db.php';

$result=$conn->query("SELECT * FROM destination ORDER BY Name ASC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Destinations</title>
<style>
*{box-sizing:border-box}body{font-family:Arial;margin:0;background:linear-gradient(135deg,#eef2ff,#f0fdf4);color:#1e293b}
.container{max-width:1100px;margin:35px auto;padding:20px}
.head{background:linear-gradient(135deg,#4f46e5,#7c3aed);color:white;padding:25px;border-radius:18px;text-align:center;margin-bottom:25px}
.head h2{margin:0 0 8px}.head a{color:white;text-decoration:none}
.destination-container{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:22px}
.destination-card{background:white;border-radius:16px;overflow:hidden;box-shadow:0 6px 16px #0002;transition:.2s}
.destination-card:hover{transform:translateY(-5px)}
.destination-card img{width:100%;height:190px;object-fit:cover}
.no-image{height:190px;background:#e2e8f0;display:flex;align-items:center;justify-content:center;color:#64748b}
.info{padding:18px}.info h3{margin:0 0 12px;color:#4f46e5;font-size:23px}
.info p{line-height:1.5;margin:8px 0}.rating{color:#f59e0b;font-weight:bold}
.button{display:inline-block;padding:9px 12px;background:#4f46e5;color:white;text-decoration:none;border-radius:7px;margin:8px 5px 0 0;font-size:14px}
.button:hover{background:#3730a3}
</style>
</head>
<body>

<div class="container">
<div class="head">
<h2>🌍 Available Destinations</h2>
<a href="dashboard.php">← Back to Dashboard</a>
</div>

<div class="destination-container">
<?php if($result&&$result->num_rows): while($row=$result->fetch_assoc()): ?>
<div class="destination-card">

<?php if($row['image']): ?>
<img src="uploads/destinations/<?=htmlspecialchars($row['image'])?>" alt="<?=htmlspecialchars($row['Name'])?>">
<?php else: ?>
<div class="no-image">📷 No Picture Available</div>
<?php endif; ?>

<div class="info">
<h3><?=htmlspecialchars($row['Name'])?></h3>
<p><strong>📍 Location:</strong> <?=htmlspecialchars($row['gps']??'Not available')?></p>
<p><strong>📝 Description:</strong><br><?=htmlspecialchars($row['Description']??'No description available')?></p>
<p class="rating">⭐ <?=htmlspecialchars($row['avg_rating'])?>/5</p>
<p><strong>🛡️ Safety:</strong> <?=htmlspecialchars($row['Peace_ind']??'Not available')?></p>

<a class="button" href="seasonal_advisory.php?dest_id=<?=$row['Dest_ID']?>">🌤️ Seasonal Advisory</a>
<a class="button" href="create_plan.php?dest_id=<?=$row['Dest_ID']?>">🗺️ Create Plan</a>
</div>
</div>
<?php endwhile; else: ?>
<p>No destinations available.</p>
<?php endif;?>
</div>
</div>

</body>
</html>