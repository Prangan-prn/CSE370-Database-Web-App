<?php
session_start();
if(!isset($_SESSION['user_id'])){header("Location: login.php");exit();}
include 'db.php';

$message="";
$today = date('Y-m-d');
$selected_dest_id=isset($_GET['dest_id'])?(int)$_GET['dest_id']:0;
$destination_result=$conn->query("SELECT Dest_ID,Name FROM destination ORDER BY Name ASC");
$guides_result=$conn->query("SELECT guide_id,guide_name,hourly_rate FROM tour_guide ORDER BY guide_name ASC");

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $user_id=$_SESSION['user_id'];
    $plan_title=trim($_POST['plan_title']??'');
    $start_date=$_POST['start_date']??'';
    $end_date=$_POST['end_date']??'';
    $budget=(float)($_POST['budget']??0);
    $dest_id=(int)($_POST['Dest_ID']??0);
    $guide_id=!empty($_POST['guide_id'])?(int)$_POST['guide_id']:NULL;

    if($plan_title===''||$dest_id<=0){
        $message="Please enter a trip title and select a destination.";
    } elseif($start_date < $today) {
        $message="Start date cannot be in the past.";
    } elseif($end_date < $start_date) {
        $message="End date cannot be before start date.";
    } elseif($budget<0) {
        $message="Budget cannot be negative.";
    } else {
        $check=$conn->prepare("SELECT Dest_ID FROM destination WHERE Dest_ID=?");
        $check->bind_param("i",$dest_id);
        $check->execute();
        
        if($check->get_result()->num_rows!==1){
            $message="Selected destination does not exist.";
        } else {
            $is_booked=false;
            if($guide_id!==NULL){
                $g_check=$conn->prepare("SELECT plan_id FROM travel_plan WHERE guide_id=? AND (start_date<=? AND end_date>=?)");
                $g_check->bind_param("iss",$guide_id,$end_date,$start_date);
                $g_check->execute();
                if($g_check->get_result()->num_rows>0){
                    $is_booked=true;
                    $message="This guide is already booked for the selected dates.";
                }
                $g_check->close();
            }
            if(!$is_booked){
                $stmt=$conn->prepare("INSERT INTO travel_plan (user_id, Dest_ID, plan_title, start_date, end_date, budget, guide_id) VALUES(?,?,?,?,?,?,?)");
                $stmt->bind_param("iisssdi", $user_id, $dest_id, $plan_title, $start_date, $end_date, $budget, $guide_id);
                $message=$stmt->execute()?"Travel Plan Saved Successfully!":"Error: ".$stmt->error;
                $stmt->close();
            }
        }
        $check->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Create Travel Plan</title>
<style>
*{box-sizing:border-box}body{font-family:Arial;margin:0;background:linear-gradient(135deg,#eef2ff,#f0fdf4);color:#1e293b}
.container{max-width:600px;margin:35px auto;padding:20px}
.head{background:linear-gradient(135deg,#4f46e5,#7c3aed);color:white;padding:25px;border-radius:18px;text-align:center;margin-bottom:25px;box-shadow:0 6px 16px #0002}
.head h2{margin:0 0 8px}.head a{color:white;text-decoration:none}
form{background:white;padding:25px;border-radius:16px;box-shadow:0 6px 16px #0002}
label{display:block;font-weight:bold;margin:12px 0 6px}
input,select{width:100%;padding:10px;border:1px solid #cbd5e1;border-radius:7px}
input:focus,select:focus{outline:none;border-color:#4f46e5}
button{padding:10px 18px;margin-top:18px;background:#4f46e5;color:white;border:0;border-radius:7px;cursor:pointer}
button:hover{background:#3730a3}
.message{background:#f0fdf4;color:#15803d;padding:10px;border-radius:7px}
.error{background:#fef2f2;color:#dc2626;padding:10px;border-radius:7px}
</style>
</head>
<body>

<div class="container">
<div class="head">
<h2>📝 Create a New Travel Plan</h2>
<a href="dashboard.php">← Back to Dashboard</a>
</div>

<?php if(!empty($message)): ?>
<p class="<?=strpos($message,'Error')!==false||strpos($message,'cannot')!==false||strpos($message,'Please')!==false||strpos($message,'does not')!==false||strpos($message,'booked')!==false||strpos($message,'past')!==false?'error':'message'?>">
<?=htmlspecialchars($message)?></p>
<?php endif;?>

<form method="POST" action="create_plan.php">
<label>Trip Title:</label>
<input type="text" name="plan_title" placeholder="Example: Tokyo Holiday" required>

<label>Destination:</label>
<select name="Dest_ID" required>
<option value="">-- Select Destination --</option>
<?php if($destination_result):while($destination=$destination_result->fetch_assoc()): ?>
<option value="<?=$destination['Dest_ID']?>" <?=($selected_dest_id==$destination['Dest_ID'])?'selected':''?>>
<?=htmlspecialchars($destination['Name'])?></option>
<?php endwhile;endif;?>
</select>

<label>Start Date:</label>
<input type="date" name="start_date" min="<?=$today?>" required>

<label>End Date:</label>
<input type="date" name="end_date" min="<?=$today?>" required>

<label>Budget ($):</label>
<input type="number" step="0.01" min="0" name="budget" required>

<label>Assign Tour Guide:</label>
<select name="guide_id">
<option value="">-- No Guide --</option>
<?php if($guides_result):while($guide=$guides_result->fetch_assoc()): ?>
<option value="<?=$guide['guide_id']?>"><?=htmlspecialchars($guide['guide_name'])?> ($<?=number_format($guide['hourly_rate'],2)?>/hr)</option>
<?php endwhile;endif;?>
</select>

<button type="submit">Save Travel Plan</button>
</form>
</div>

</body>
</html>