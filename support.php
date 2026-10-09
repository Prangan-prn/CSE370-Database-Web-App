<?php
session_start();
if(!isset($_SESSION['user_id'])){header("Location: login.php");exit();}
include 'db.php';

$user_id=$_SESSION['user_id'];$message="";$message_type="";

if($_SERVER["REQUEST_METHOD"]==="POST"){
$description=trim($_POST['description']??'');
if(!$description){$message="Please enter a description.";$message_type="error";}
else{
$stmt=$conn->prepare("INSERT INTO support_ticket (user_id,Description) VALUES (?,?)");
$stmt->bind_param("is",$user_id,$description);
if($stmt->execute()){$message="Support ticket created successfully!";$message_type="success";}
else{$message="Error creating support ticket.";$message_type="error";}
$stmt->close();
}
}

$stmt=$conn->prepare("SELECT ticket_id,Description,Status,cr_date FROM support_ticket WHERE user_id=? ORDER BY cr_date DESC");
$stmt->bind_param("i",$user_id);$stmt->execute();$result=$stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<title>Support Tickets</title>
<style>
*{box-sizing:border-box}body{font-family:Arial;margin:0;background:linear-gradient(135deg,#eef2ff,#f0fdf4);color:#1e293b}
.container{max-width:800px;margin:35px auto;padding:20px}
.head{background:linear-gradient(135deg,#4f46e5,#7c3aed);color:white;padding:25px;border-radius:18px;text-align:center;margin-bottom:25px;box-shadow:0 6px 16px #0002}
.head h2{margin:0 0 8px}.head a{color:white;text-decoration:none}
.box{background:white;padding:25px;border-radius:16px;box-shadow:0 6px 16px #0002;margin-bottom:22px}
h3{margin:0 0 18px;color:#4f46e5}
label{display:block;font-weight:bold;margin-bottom:6px}
textarea{width:100%;height:120px;padding:10px;border:1px solid #cbd5e1;border-radius:7px;margin:8px 0 15px;resize:vertical}
textarea:focus{outline:none;border-color:#4f46e5}
button{padding:10px 18px;background:#4f46e5;color:white;border:0;border-radius:7px;cursor:pointer}
button:hover{background:#3730a3}
.success{background:#f0fdf4;color:#15803d;padding:10px;border-radius:7px}
.error{background:#fef2f2;color:#dc2626;padding:10px;border-radius:7px}
.ticket{border:1px solid #cbd5e1;padding:15px;margin-bottom:15px;border-radius:7px}
.status{font-weight:bold;color:#4f46e5}
</style>
</head>

<body>
<div class="container">

<div class="head">
<h2>🎧 Support Center</h2>
<a href="dashboard.php">← Back to Dashboard</a>
</div>

<div class="box">
<h3>Create Support Ticket</h3>

<?php if($message): ?>
<p class="<?=$message_type?>"><?=htmlspecialchars($message)?></p>
<?php endif;?>

<form method="POST">
<label>Describe your problem:</label>
<textarea name="description" placeholder="Enter your problem or question..." required></textarea>
<button type="submit">Submit Ticket</button>
</form>
</div>

<div class="box">
<h3>My Support Tickets</h3>

<?php if($result->num_rows>0): ?>
<?php while($row=$result->fetch_assoc()): ?>
<div class="ticket">
<strong>Ticket #<?=$row['ticket_id']?></strong>
<p><?=nl2br(htmlspecialchars($row['Description']))?></p>
<p><span class="status">Status:</span> <?=htmlspecialchars($row['Status'])?></p>
<small>Created: <?=htmlspecialchars($row['cr_date'])?></small>
</div>
<?php endwhile;?>
<?php else: ?>
<p>You have not created any support tickets yet.</p>
<?php endif;?>

</div>
</div>
</body>
</html>

<?php $stmt->close(); ?>
```
