```php
<?php
session_start();
if(!isset($_SESSION['user_id'])){header("Location: login.php");exit();}
include 'db.php';

$user_id=$_SESSION['user_id'];$message="";$message_type="";

if($_SERVER["REQUEST_METHOD"]==="POST"){
$preference=trim($_POST['preference']??'');
if(!$preference){$message="Please enter a travel preference.";$message_type="error";}
else{
$stmt=$conn->prepare("INSERT INTO user_trpref (user_id,Travel_plan) VALUES (?,?)");
$stmt->bind_param("is",$user_id,$preference);
if($stmt->execute()){$message="Travel preference added successfully!";$message_type="success";}
else{$message=$stmt->errno==1062?"You already have this preference.":"Error adding preference.";$message_type="error";}
$stmt->close();
}
}

if(isset($_GET['delete'])){
$preference=$_GET['delete'];
$stmt=$conn->prepare("DELETE FROM user_trpref WHERE user_id=? AND Travel_plan=?");
$stmt->bind_param("is",$user_id,$preference);$stmt->execute();$stmt->close();
header("Location: preferences.php");exit();
}

$stmt=$conn->prepare("SELECT Travel_plan FROM user_trpref WHERE user_id=? ORDER BY Travel_plan ASC");
$stmt->bind_param("i",$user_id);$stmt->execute();$result=$stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<title>Travel Preferences</title>
<style>
*{box-sizing:border-box}body{font-family:Arial;margin:0;background:linear-gradient(135deg,#eef2ff,#f0fdf4);color:#1e293b}
.container{max-width:700px;margin:35px auto;padding:20px}
.head{background:linear-gradient(135deg,#4f46e5,#7c3aed);color:white;padding:25px;border-radius:18px;text-align:center;margin-bottom:25px;box-shadow:0 6px 16px #0002}
.head h2{margin:0 0 8px}.head a{color:white;text-decoration:none}
.box{background:white;padding:25px;border-radius:16px;box-shadow:0 6px 16px #0002;margin-bottom:22px}
h3{margin:0 0 18px;color:#4f46e5}
label{display:block;font-weight:bold;margin-bottom:6px}
input{width:100%;padding:10px;border:1px solid #cbd5e1;border-radius:7px;margin:8px 0 15px}
input:focus{outline:none;border-color:#4f46e5}
button{padding:10px 18px;background:#4f46e5;color:white;border:0;border-radius:7px;cursor:pointer}
button:hover{background:#3730a3}
.preference{padding:12px;border:1px solid #cbd5e1;margin-bottom:10px;border-radius:7px}
.delete{float:right;color:#dc2626;text-decoration:none}.delete:hover{color:#991b1b}
.success{background:#f0fdf4;color:#15803d;padding:10px;border-radius:7px}
.error{background:#fef2f2;color:#dc2626;padding:10px;border-radius:7px}
</style>
</head>

<body>
<div class="container">

<div class="head">
<h2>⭐ My Travel Preferences</h2>
<a href="dashboard.php">← Back to Dashboard</a>
</div>

<div class="box">
<h3>Add Travel Preference</h3>

<?php if($message): ?>
<p class="<?=$message_type?>"><?=htmlspecialchars($message)?></p>
<?php endif;?>

<form method="POST">
<label>Travel Preference:</label>
<input type="text" name="preference" placeholder="Example: Adventure, History, Food, Nature" required>
<button type="submit">Add Preference</button>
</form>
</div>

<div class="box">
<h3>Saved Preferences</h3>

<?php if($result->num_rows>0): ?>
<?php while($row=$result->fetch_assoc()): ?>
<div class="preference">
<?=htmlspecialchars($row['Travel_plan'])?>
<a class="delete" href="preferences.php?delete=<?=urlencode($row['Travel_plan'])?>" onclick="return confirm('Delete this preference?');">Delete</a>
</div>
<?php endwhile;?>
<?php else: ?>
<p>No travel preferences saved yet.</p>
<?php endif;?>

</div>
</div>
</body>
</html>

<?php $stmt->close(); ?>
```
