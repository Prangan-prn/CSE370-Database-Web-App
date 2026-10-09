<?php
session_start();
if(!isset($_SESSION['user_id'])){header("Location: login.php");exit();}
include 'db.php';

$result=$conn->query("SELECT g.Media_ID,g.image,g.up_d,g.user_id,u.name
FROM gallery_media g INNER JOIN user u ON g.user_id=u.user_id ORDER BY g.up_d DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Travel Gallery</title>
<style>
*{box-sizing:border-box}body{font-family:Arial;margin:0;background:linear-gradient(135deg,#eef2ff,#f0fdf4);color:#1e293b}
.container{max-width:1100px;margin:35px auto;padding:20px}
.header{background:linear-gradient(135deg,#4f46e5,#7c3aed);color:white;padding:25px;border-radius:18px;text-align:center;margin-bottom:25px;box-shadow:0 6px 18px #0002}
.header h2{margin:0 0 8px}.header a{color:white;text-decoration:none}
.button{display:inline-block;padding:10px 15px;background:#10b981;color:white;text-decoration:none;border-radius:7px;font-weight:bold;margin-top:8px}
.button:hover{background:#059669}
.gallery{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:22px}
.card{background:white;padding:12px;border-radius:15px;box-shadow:0 6px 15px #0002;transition:.2s;overflow:hidden}
.card:hover{transform:translateY(-5px);box-shadow:0 10px 22px #0003}
.card img{width:100%;height:220px;object-fit:cover;border-radius:10px}
.info{padding:10px 3px;line-height:1.6}
.feedback{display:inline-block;color:#4f46e5;text-decoration:none;font-weight:bold;margin-top:5px}
.feedback:hover{text-decoration:underline}
.delete-button{margin-top:8px;padding:8px 12px;background:#ef4444;color:white;border:0;border-radius:6px;cursor:pointer;font-weight:bold}
.delete-button:hover{background:#dc2626}
</style>
</head>
<body>

<div class="container">
<div class="header">
<h2>📸 Travel Gallery</h2>
<p><a href="dashboard.php">← Back to Dashboard</a></p>
<a class="button" href="upload_gallery.php">➕ Upload New Image</a>
</div>

<div class="gallery">
<?php if($result&&$result->num_rows>0): while($row=$result->fetch_assoc()): ?>
<div class="card">
<img src="uploads/<?=htmlspecialchars($row['image'])?>" alt="Travel Image">
<div class="info">
<strong>👤 Uploaded by:</strong> <?=htmlspecialchars($row['name'])?><br>
<small>📅 <?=htmlspecialchars($row['up_d'])?></small><br>
<a class="feedback" href="feedback.php?media_id=<?=$row['Media_ID']?>">⭐ Give Feedback</a>

<?php if($row['user_id']==$_SESSION['user_id']): ?>
<form action="delete_gallery.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this image?');">
<input type="hidden" name="media_id" value="<?=$row['Media_ID']?>">
<button type="submit" class="delete-button">🗑️ Delete</button>
</form>
<?php endif; ?>
</div>
</div>
<?php endwhile; else: ?>
<p>No images have been uploaded yet.</p>
<?php endif;?>
</div>
</div>

</body>
</html>