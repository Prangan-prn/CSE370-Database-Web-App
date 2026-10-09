<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare(
    "SELECT tp.*, tg.guide_name, tg.contact_number,
            d.Name AS destination_name
     FROM travel_plan tp
     LEFT JOIN tour_guide tg ON tp.guide_id = tg.guide_id
     LEFT JOIN destination d ON tp.Dest_ID = d.Dest_ID
     WHERE tp.user_id = ?
     ORDER BY tp.start_date ASC"
);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<title>My Saved Travel Plans</title>
<style>
*{box-sizing:border-box}
body{margin:0;font-family:Arial,sans-serif;background:linear-gradient(135deg,#e3f2fd,#f3e5f5);color:#333}
.container{max-width:1100px;margin:45px auto;padding:20px}
.header{background:linear-gradient(135deg,#1565c0,#7b1fa2);color:white;padding:25px;border-radius:15px;text-align:center;box-shadow:0 5px 15px #0002}
.header h2{margin:0 0 15px}
.btn{display:inline-block;padding:9px 15px;border-radius:20px;text-decoration:none;margin:3px}
.back{background:#ffffff33;color:white}
.create{background:white;color:#1565c0}
.box{background:white;margin-top:25px;padding:20px;border-radius:15px;box-shadow:0 3px 12px #0002;overflow-x:auto}
table{width:100%;border-collapse:collapse}
th{background:#1565c0;color:white;padding:14px}
td{padding:13px;text-align:center;border-bottom:1px solid #ddd}
tr:hover{background:#f3e5f5}
.title{font-weight:bold;color:#7b1fa2}
.budget{font-weight:bold;color:#2e7d32}
.none{color:#888}
</style>
</head>

<body>
<div class="container">

<div class="header">
<h2>🗺️ My Saved Travel Plans</h2>
<a class="btn back" href="dashboard.php">← Dashboard</a>
<a class="btn create" href="create_plan.php">+ Create New Plan</a>
</div>

<div class="box">
<table>
<tr>
<th>Trip Title</th>
<th>Start Date</th>
<th>End Date</th>
<th>Budget</th>
<th>Assigned Guide</th>
<th>Guide Contact</th>
<th>Destination</th>
</tr>

<?php if($result && $result->num_rows > 0): ?>
<?php while($row=$result->fetch_assoc()): ?>
<tr>
<td class="title"><?= htmlspecialchars($row['plan_title']) ?></td>
<td><?= htmlspecialchars($row['start_date']) ?></td>
<td><?= htmlspecialchars($row['end_date']) ?></td>
<td class="budget">$<?= number_format($row['budget'],2) ?></td>
<td><?= $row['guide_name'] ? htmlspecialchars($row['guide_name']) : '<span class="none">None</span>' ?></td>
<td><?= $row['contact_number'] ? htmlspecialchars($row['contact_number']) : '<span class="none">N/A</span>' ?></td>
<td><?= $row['destination_name'] ? htmlspecialchars($row['destination_name']) : '<span class="none">None</span>' ?></td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr><td colspan="7">No saved travel plans found.</td></tr>
<?php endif; ?>

</table>
</div>

</div>
</body>
</html>

<?php $stmt->close(); ?>