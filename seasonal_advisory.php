<?php
session_start();
if(!isset($_SESSION['user_id'])){header("Location: login.php");exit();}
include 'db.php';

$dest_id=isset($_GET['dest_id'])?(int)$_GET['dest_id']:0;

$stmt=$conn->prepare("SELECT Dest_ID,Name,Description FROM destination WHERE Dest_ID=?");
$stmt->bind_param("i",$dest_id);
$stmt->execute();
$destination=$stmt->get_result()->fetch_assoc();
$stmt->close();

if(!$destination) die("Destination not found.");

$stmt=$conn->prepare("SELECT Advis_id,Bst_month,Reason,trans_cost FROM seasonal_advisory WHERE Dest_id=? ORDER BY Advis_id ASC");
$stmt->bind_param("i",$dest_id);
$stmt->execute();
$result=$stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<title>Seasonal Advisory</title>
<style>
body{font-family:Arial,sans-serif;margin:30px;background:#f5f7fa}
.container{max-width:900px;margin:auto}
.destination,.advisory{background:white;padding:20px;border-radius:10px;margin-bottom:20px;box-shadow:0 2px 7px rgba(0,0,0,.1)}
.advisory{margin-bottom:15px;border-left:5px solid #1565c0;border-radius:5px}
.month{color:#1565c0;font-size:20px;font-weight:bold}
.button{display:inline-block;padding:9px 14px;background:#1565c0;color:white;text-decoration:none;border-radius:5px}
</style>
</head>
<body>

<div class="container">
<p><a href="destinations.php">← Back to Destinations</a></p>

<div class="destination">
<h2>Seasonal Advisory: <?=htmlspecialchars($destination['Name'])?></h2>
<p><?=htmlspecialchars($destination['Description'])?></p>
</div>

<h3>Best Time to Visit</h3>

<?php if($result->num_rows>0): ?>
<?php while($row=$result->fetch_assoc()): ?>
<div class="advisory">
<div class="month"><?=htmlspecialchars($row['Bst_month'])?></div>
<p><strong>Reason:</strong><br><?=htmlspecialchars($row['Reason'])?></p>
<p><strong>Estimated Transportation Cost:</strong> $<?=number_format($row['trans_cost'],2)?></p>
</div>
<?php endwhile; ?>
<?php else: ?>
<p>No seasonal advisory is available for this destination yet.</p>
<?php endif; ?>

<p><a class="button" href="create_plan.php?dest_id=<?=$dest_id?>">Create Travel Plan</a></p>
</div>

</body>
</html>