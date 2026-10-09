<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';
$result = $conn->query("SELECT * FROM tour_guide");
?>

<!DOCTYPE html>
<html>
<head>
<title>Tour Guides</title>
<style>
*{box-sizing:border-box}
body{margin:0;font-family:Arial,sans-serif;background:linear-gradient(135deg,#e3f2fd,#f3e5f5);color:#333}
.container{max-width:1100px;margin:45px auto;padding:20px}
.header{background:linear-gradient(135deg,#1565c0,#7b1fa2);color:white;padding:25px;border-radius:15px;text-align:center;box-shadow:0 5px 15px #0002}
.header h2{margin:0 0 10px}
.back{display:inline-block;color:white;text-decoration:none;background:#ffffff33;padding:8px 15px;border-radius:20px}
.box{background:white;margin-top:25px;padding:20px;border-radius:15px;box-shadow:0 3px 12px #0002;overflow-x:auto}
table{width:100%;border-collapse:collapse}
th{background:#1565c0;color:white;padding:14px}
td{padding:13px;text-align:center;border-bottom:1px solid #ddd}
tr:hover{background:#f3e5f5}
.yes{color:#2e7d32;font-weight:bold}
.no{color:#c62828;font-weight:bold}
.listen{background:#7b1fa2;color:white;padding:7px 12px;border-radius:15px;text-decoration:none}
.listen:hover{background:#4a148c}
.rate{font-weight:bold;color:#1565c0}
</style>
</head>

<body>
<div class="container">

<div class="header">
<h2>🧭 Available Tour Guides</h2>
<a class="back" href="dashboard.php">← Back to Dashboard</a>
</div>

<div class="box">
<table>
<tr>
<th>Name</th>
<th>Contact</th>
<th>Hourly Rate</th>
<th>Physical Guide</th>
<th>Virtual Guide</th>
<th>Podcast</th>
</tr>

<?php if ($result && $result->num_rows > 0): ?>
<?php while ($row=$result->fetch_assoc()): ?>
<tr>
<td><strong><?= htmlspecialchars($row['guide_name']) ?></strong></td>
<td><?= htmlspecialchars($row['contact_number']) ?></td>
<td class="rate">$<?= number_format($row['hourly_rate'],2) ?></td>
<td class="<?= $row['Phy_guideFlag']?'yes':'no' ?>">
<?= $row['Phy_guideFlag']?'Yes':'No' ?>
</td>
<td class="<?= $row['Vir_guideFlag']?'yes':'no' ?>">
<?= $row['Vir_guideFlag']?'Yes':'No' ?>
</td>
<td>
<?php if(!empty($row['Pod_URL']) && filter_var($row['Pod_URL'],FILTER_VALIDATE_URL)): ?>
<a class="listen" href="<?= htmlspecialchars($row['Pod_URL']) ?>" target="_blank" rel="noopener noreferrer">🎧 Listen</a>
<?php else: ?>
N/A
<?php endif; ?>
</td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr><td colspan="6">No guides found.</td></tr>
<?php endif; ?>

</table>
</div>

</div>
</body>
</html>