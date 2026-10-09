```php
<?php
session_start();
if(!isset($_SESSION['user_id'])){header("Location: login.php");exit();}
if($_SESSION['user_type']!=='admin')die("Access denied.");
include 'db.php';

$message="";$message_type="";

if(isset($_GET['delete'])){
$id=(int)$_GET['delete'];
$stmt=$conn->prepare("DELETE FROM tour_guide WHERE guide_id=?");
$stmt->bind_param("i",$id);
if($stmt->execute()){$message="Tour guide deleted successfully.";$message_type="success";}
else{$message="Error deleting tour guide.";$message_type="error";}
$stmt->close();
}

if($_SERVER["REQUEST_METHOD"]==="POST"){
$name=trim($_POST['guide_name']??'');
$contact=trim($_POST['contact_number']??'');
$rate=(float)($_POST['hourly_rate']??0);
$physical=isset($_POST['physical'])?1:0;
$virtual=isset($_POST['virtual'])?1:0;
$podcast=trim($_POST['podcast']??'');

if($name===''||$contact===''){
$message="Guide name and contact number are required.";$message_type="error";
}else{
$stmt=$conn->prepare("INSERT INTO tour_guide (guide_name,contact_number,hourly_rate,Phy_guideFlag,Vir_guideFlag,Pod_URL) VALUES(?,?,?,?,?,?)");
$stmt->bind_param("ssdiis",$name,$contact,$rate,$physical,$virtual,$podcast);
if($stmt->execute()){$message="Tour guide added successfully.";$message_type="success";}
else{$message="Error adding tour guide: ".$stmt->error;$message_type="error";}
$stmt->close();
}
}

$result=$conn->query("SELECT guide_id,guide_name,contact_number,hourly_rate,Phy_guideFlag,Vir_guideFlag,Pod_URL FROM tour_guide ORDER BY guide_name ASC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Tour Guides</title>
<style>
*{box-sizing:border-box}body{font-family:Arial;margin:0;background:linear-gradient(135deg,#eef2ff,#f0fdf4);color:#1e293b}
.container{max-width:900px;margin:35px auto;padding:20px}
.head{background:linear-gradient(135deg,#4f46e5,#7c3aed);color:white;padding:25px;border-radius:18px;text-align:center;margin-bottom:25px;box-shadow:0 6px 16px #0002}
.head h2{margin:0 0 8px}.head a{color:white;text-decoration:none}
.box{background:white;padding:25px;border-radius:16px;box-shadow:0 6px 16px #0002;margin-bottom:22px}
h3{margin:0 0 18px;color:#4f46e5}
label{display:block;font-weight:bold;margin:12px 0 6px}
input[type=text],input[type=number]{width:100%;padding:10px;border:1px solid #cbd5e1;border-radius:7px;margin:5px 0 15px}
input:focus{outline:none;border-color:#4f46e5}
.checkbox{width:auto!important;margin:0 5px 0 0!important}
button{padding:10px 18px;background:#4f46e5;color:white;border:0;border-radius:7px;cursor:pointer}
button:hover{background:#3730a3}
.guide{border:1px solid #cbd5e1;padding:18px;margin-bottom:15px;border-radius:7px}
.guide h3{margin:0 0 12px}
.delete{color:#dc2626;text-decoration:none}.delete:hover{color:#991b1b}
.success{background:#f0fdf4;color:#15803d;padding:10px;border-radius:7px}
.error{background:#fef2f2;color:#dc2626;padding:10px;border-radius:7px}
</style>
</head>

<body>
<div class="container">

<div class="head">
<h2>🧑‍✈️ Manage Tour Guides</h2>
<a href="dashboard.php">← Back to Dashboard</a>
</div>

<?php if(!empty($message)): ?>
<p class="<?=$message_type?>"><?=htmlspecialchars($message)?></p>
<?php endif;?>

<div class="box">
<h3>Add New Tour Guide</h3>

<form method="POST">
<label>Guide Name:</label>
<input type="text" name="guide_name" placeholder="Example: John Smith" required>

<label>Contact Number:</label>
<input type="text" name="contact_number" placeholder="Example: +8801712345678" required>

<label>Hourly Rate:</label>
<input type="number" name="hourly_rate" min="0" step="0.01" value="0">

<label><input class="checkbox" type="checkbox" name="physical"> Physical Guide</label>
<label><input class="checkbox" type="checkbox" name="virtual"> Virtual Guide</label>

<label>Podcast URL:</label>
<input type="text" name="podcast" placeholder="https://example.com/podcast">

<button type="submit">Add Tour Guide</button>
</form>
</div>

<div class="box">
<h3>Existing Tour Guides</h3>

<?php if($result&&$result->num_rows>0): ?>
<?php while($row=$result->fetch_assoc()): ?>
<div class="guide">

<h3><?=htmlspecialchars($row['guide_name'])?></h3>
<p><strong>Contact:</strong> <?=htmlspecialchars($row['contact_number'])?></p>
<p><strong>Hourly Rate:</strong> <?=htmlspecialchars($row['hourly_rate'])?></p>
<p><strong>Physical Guide:</strong> <?=$row['Phy_guideFlag']?"Yes":"No"?></p>
<p><strong>Virtual Guide:</strong> <?=$row['Vir_guideFlag']?"Yes":"No"?></p>

<?php if(!empty($row['Pod_URL'])): ?>
<p><strong>Podcast:</strong> <?=htmlspecialchars($row['Pod_URL'])?></p>
<?php endif;?>

<a class="delete" href="admin_guides.php?delete=<?=$row['guide_id']?>" onclick="return confirm('Delete this tour guide?');">Delete Guide</a>

</div>
<?php endwhile;?>
<?php else: ?>
<p>No tour guides found.</p>
<?php endif;?>

</div>
</div>
</body>
</html>

<?php $conn->close(); ?>
```
