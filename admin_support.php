<?php
session_start();
if(!isset($_SESSION['user_id'])){header("Location: login.php");exit();}
if($_SESSION['user_type']!=='admin')die("Access denied. Admins only.");
include 'db.php';

$message="";
if($_SERVER["REQUEST_METHOD"]==="POST"){
    $ticket_id=(int)$_POST['ticket_id'];
    $status=$_POST['status'];
    $allowed=['Open','In Progress','Resolved'];

    if(in_array($status,$allowed)){
        $stmt=$conn->prepare("UPDATE support_ticket SET Status=? WHERE ticket_id=?");
        $stmt->bind_param("si",$status,$ticket_id);
        $message=$stmt->execute()?"Ticket status updated successfully!":"Error updating ticket.";
        $stmt->close();
    }
}

$result=$conn->query("SELECT s.ticket_id,s.Description,s.Status,s.cr_date,u.name,u.email
FROM support_ticket s INNER JOIN user u ON s.user_id=u.user_id ORDER BY s.cr_date DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Support Tickets</title>
<style>
*{box-sizing:border-box}body{font-family:Arial;margin:0;background:linear-gradient(135deg,#eef2ff,#f0fdf4);color:#1e293b}
.container{max-width:1100px;margin:35px auto;padding:20px}
.head{background:linear-gradient(135deg,#4f46e5,#7c3aed);color:white;padding:22px;border-radius:16px;text-align:center;box-shadow:0 6px 18px #0002}
.head h2{margin:0 0 8px}.head a{color:white;text-decoration:none}
.box{margin-top:22px;background:white;padding:20px;border-radius:16px;box-shadow:0 5px 15px #0001;overflow-x:auto}
.message{background:#dcfce7;color:#166534;padding:12px;border-radius:8px;margin:15px 0}
table{width:100%;border-collapse:collapse;min-width:850px}
th,td{padding:12px;border-bottom:1px solid #e2e8f0;text-align:left}
th{background:#4f46e5;color:white}
tr:hover{background:#f8fafc}
.status{font-weight:bold;padding:6px 10px;border-radius:15px;background:#e0e7ff;color:#3730a3}
select,button{padding:8px;border-radius:6px}
select{border:1px solid #cbd5e1;background:white}
button{background:#4f46e5;color:white;border:0;cursor:pointer;font-weight:bold}
button:hover{background:#3730a3}
</style>
</head>
<body>

<div class="container">
<div class="head">
<h2>🎫 Manage Support Tickets</h2>
<a href="dashboard.php">← Back to Dashboard</a>
</div>

<?php if($message): ?><p class="message"><?=htmlspecialchars($message)?></p><?php endif;?>

<div class="box">
<?php if($result&&$result->num_rows>0):?>
<table>
<tr><th>Ticket</th><th>User</th><th>Email</th><th>Description</th><th>Status</th><th>Created</th><th>Update</th></tr>

<?php while($row=$result->fetch_assoc()):?>
<tr>
<td>#<?=$row['ticket_id']?></td>
<td><?=htmlspecialchars($row['name'])?></td>
<td><?=htmlspecialchars($row['email'])?></td>
<td><?=nl2br(htmlspecialchars($row['Description']))?></td>
<td><span class="status"><?=htmlspecialchars($row['Status'])?></span></td>
<td><?=htmlspecialchars($row['cr_date'])?></td>
<td>
<form method="POST">
<input type="hidden" name="ticket_id" value="<?=$row['ticket_id']?>">
<select name="status">
<option <?=$row['Status']=='Open'?'selected':''?>>Open</option>
<option <?=$row['Status']=='In Progress'?'selected':''?>>In Progress</option>
<option <?=$row['Status']=='Resolved'?'selected':''?>>Resolved</option>
</select>
<button>Update</button>
</form>
</td>
</tr>
<?php endwhile;?>
</table>
<?php else:?><p>No support tickets found.</p><?php endif;?>
</div>
</div>
</body>
</html>