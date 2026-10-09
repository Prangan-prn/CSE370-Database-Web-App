<?php
session_start();
if(!isset($_SESSION['user_id'])){header("Location: login.php");exit();}
if($_SESSION['user_type']!=='admin')die("Access denied.");
include 'db.php';

if(isset($_GET['delete'])){
    $id=(int)$_GET['delete'];
    $stmt=$conn->prepare("SELECT image FROM destination WHERE Dest_ID=?");
    $stmt->bind_param("i",$id);$stmt->execute();
    $img=$stmt->get_result()->fetch_assoc()['image']??'';$stmt->close();

    if($img&&file_exists("uploads/destinations/".$img))unlink("uploads/destinations/".$img);

    $stmt=$conn->prepare("DELETE FROM destination WHERE Dest_ID=?");
    $stmt->bind_param("i",$id);$stmt->execute();$stmt->close();
    header("Location: admin_destinations.php");exit();
}

$result=$conn->query("SELECT * FROM destination ORDER BY Name ASC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Destinations</title>
<style>
*{box-sizing:border-box}body{font-family:Arial;margin:0;background:linear-gradient(135deg,#eef2ff,#f0fdf4);color:#1e293b}
.container{max-width:1100px;margin:35px auto;padding:20px}
.head{background:linear-gradient(135deg,#4f46e5,#7c3aed);color:white;padding:25px;border-radius:18px;text-align:center}
.head h2{margin:0 0 8px}.head a{color:white;text-decoration:none}
.actions{margin:20px 0}.btn{display:inline-block;padding:10px 15px;background:#4f46e5;color:white;text-decoration:none;border-radius:7px;margin-right:8px}
.btn:hover{background:#3730a3}
.box{background:white;padding:20px;border-radius:16px;box-shadow:0 5px 15px #0002;overflow-x:auto}
table{width:100%;border-collapse:collapse;min-width:850px}
th{background:#4f46e5;color:white;padding:12px;text-align:left}
td{padding:12px;border-bottom:1px solid #e2e8f0}
tr:hover{background:#f8fafc}
.pic{width:100px;height:70px;object-fit:cover;border-radius:8px}
.edit{color:#4f46e5;font-weight:bold;text-decoration:none;margin-right:12px}
.delete{color:#dc2626;font-weight:bold;text-decoration:none}
.rating{color:#f59e0b;font-weight:bold}
</style>
</head>
<body>

<div class="container">
<div class="head">
<h2>🌍 Manage Destinations</h2>
<a href="admin.php">← Admin Dashboard</a>
</div>

<div class="actions">
<a class="btn" href="admin_add_destination.php">➕ Add Destination</a>
</div>

<div class="box">
<table>
<tr><th>Picture</th><th>Name</th><th>Location</th><th>Rating</th><th>Safety</th><th>Actions</th></tr>

<?php if($result&&$result->num_rows): while($row=$result->fetch_assoc()): ?>
<tr>
<td>
<?php if($row['image']): ?>
<img class="pic" src="uploads/destinations/<?=htmlspecialchars($row['image'])?>">
<?php else: ?>No Image<?php endif;?>
</td>
<td><strong><?=htmlspecialchars($row['Name'])?></strong></td>
<td><?=htmlspecialchars($row['gps'])?></td>
<td class="rating">★ <?=$row['avg_rating']?></td>
<td><?=htmlspecialchars($row['Peace_ind'])?></td>
<td>
<a class="edit" href="admin_edit_destination.php?id=<?=$row['Dest_ID']?>">Edit</a>
<a class="delete" href="admin_destinations.php?delete=<?=$row['Dest_ID']?>" onclick="return confirm('Delete this destination and its picture?');">Delete</a>
</td>
</tr>
<?php endwhile; else: ?>
<tr><td colspan="6">No destinations found.</td></tr>
<?php endif;?>
</table>
</div>
</div>
</body>
</html>