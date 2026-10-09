<?php
session_start();
if(!isset($_SESSION['user_id'])){header("Location: login.php");exit();}
if($_SESSION['user_type']!=='admin')die("Access denied. Admins only.");
include 'db.php';

$message="";$type="";

if($_SERVER["REQUEST_METHOD"]==="POST"){
    $name=trim($_POST['name']);$gps=trim($_POST['gps']);
    $description=trim($_POST['description']);$rating=(float)$_POST['avg_rating'];
    $safety=trim($_POST['peace_ind']);$image="";

    if(empty($name)){$message="Destination name is required.";$type="error";}
    elseif($rating<0||$rating>5){$message="Rating must be between 0 and 5.";$type="error";}
    elseif(!empty($_FILES['image']['name'])){
        $allowed=['jpg','jpeg','png','webp'];
        $ext=strtolower(pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION));

        if(!in_array($ext,$allowed)){$message="Only JPG, JPEG, PNG and WEBP images are allowed.";$type="error";}
        elseif($_FILES['image']['size']>5*1024*1024){$message="Image must be under 5MB.";$type="error";}
        else{
            $dir="uploads/destinations/";
            if(!is_dir($dir))mkdir($dir,0777,true);
            $image=uniqid("dest_",true).".".$ext;
            move_uploaded_file($_FILES['image']['tmp_name'],$dir.$image);
        }
    }

    if(!$message){
        $stmt=$conn->prepare("INSERT INTO destination(Name,gps,Description,avg_rating,Peace_ind,image) VALUES(?,?,?,?,?,?)");
        $stmt->bind_param("sssds s",$name,$gps,$description,$rating,$safety,$image);
        $stmt->execute();$stmt->close();
        header("Location: admin_destinations.php");exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Destination</title>
<style>
*{box-sizing:border-box}body{font-family:Arial;margin:0;background:linear-gradient(135deg,#eef2ff,#f0fdf4);color:#1e293b}
.container{max-width:600px;margin:35px auto;padding:20px}
.head{background:linear-gradient(135deg,#4f46e5,#7c3aed);color:white;padding:22px;border-radius:16px;text-align:center}
.head h2{margin:0 0 8px}.head a{color:white;text-decoration:none}
form{background:white;margin-top:20px;padding:25px;border-radius:15px;box-shadow:0 5px 15px #0002}
label{display:block;margin:13px 0 5px;font-weight:bold}
input,textarea{width:100%;padding:10px;border:1px solid #cbd5e1;border-radius:7px}
textarea{height:110px;resize:vertical}
button{margin-top:18px;padding:11px 18px;background:#4f46e5;color:white;border:0;border-radius:7px;cursor:pointer;font-weight:bold}
.success,.error{padding:10px;margin-top:15px;border-radius:7px}.error{background:#fee2e2;color:#b91c1c}
</style>
</head>
<body>
<div class="container">
<div class="head"><h2>🌍 Add New Destination</h2><a href="admin_destinations.php">← Back</a></div>

<?php if($message): ?><div class="<?=$type?>"><?=htmlspecialchars($message)?></div><?php endif;?>

<form method="POST" enctype="multipart/form-data">
<label>Destination Name</label>
<input type="text" name="name" placeholder="Example: London" required>

<label>GPS / Location</label>
<input type="text" name="gps" placeholder="Example: 51.5074, -0.1278">

<label>Description</label>
<textarea name="description" placeholder="Describe the destination..."></textarea>

<label>Average Rating</label>
<input type="number" name="avg_rating" min="0" max="5" step="0.01" value="0">

<label>Safety</label>
<input type="text" name="peace_ind" placeholder="Example: Very Safe">

<label>Destination Picture</label>
<input type="file" name="image" accept="image/jpeg,image/png,image/webp">

<button type="submit">➕ Add Destination</button>
</form>
</div>
</body>
</html>