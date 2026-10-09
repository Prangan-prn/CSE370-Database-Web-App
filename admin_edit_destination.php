<?php
session_start();
if(!isset($_SESSION['user_id'])){header("Location: login.php");exit();}
if($_SESSION['user_type']!=='admin')die("Access denied.");
include 'db.php';

$id=(int)($_GET['id']??$_POST['id']??0);

$stmt=$conn->prepare("SELECT * FROM destination WHERE Dest_ID=?");
$stmt->bind_param("i",$id);$stmt->execute();
$dest=$stmt->get_result()->fetch_assoc();$stmt->close();
if(!$dest)die("Destination not found.");

if($_SERVER["REQUEST_METHOD"]==="POST"){
    $name=trim($_POST['name']);$gps=trim($_POST['gps']);
    $description=trim($_POST['description']);$rating=(float)$_POST['avg_rating'];
    $safety=trim($_POST['peace_ind']);$image=$dest['image'];

    if(isset($_POST['delete_image'])&&$image){
        if(file_exists("uploads/destinations/".$image))unlink("uploads/destinations/".$image);
        $image="";
    }

    if(!empty($_FILES['image']['name'])){
        $allowed=['jpg','jpeg','png','webp'];
        $ext=strtolower(pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION));

        if(!in_array($ext,$allowed))die("Invalid image type.");
        if($_FILES['image']['size']>5*1024*1024)die("Image must be under 5MB.");

        $dir="uploads/destinations/";
        if(!is_dir($dir))mkdir($dir,0777,true);

        if($image&&file_exists($dir.$image))unlink($dir.$image);

        $image=uniqid("dest_",true).".".$ext;
        move_uploaded_file($_FILES['image']['tmp_name'],$dir.$image);
    }

    $stmt=$conn->prepare("UPDATE destination SET Name=?,gps=?,Description=?,avg_rating=?,Peace_ind=?,image=? WHERE Dest_ID=?");
    $stmt->bind_param("sssdssi",$name,$gps,$description,$rating,$safety,$image,$id);
    $stmt->execute();$stmt->close();

    header("Location: admin_destinations.php");exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Destination</title>
<style>
*{box-sizing:border-box}body{font-family:Arial;margin:0;background:linear-gradient(135deg,#eef2ff,#f0fdf4);color:#1e293b}
.container{max-width:600px;margin:35px auto;padding:20px}
.head{background:linear-gradient(135deg,#4f46e5,#7c3aed);color:white;padding:22px;border-radius:16px;text-align:center}
.head h2{margin:0 0 8px}.head a{color:white;text-decoration:none}
form{background:white;margin-top:20px;padding:25px;border-radius:15px;box-shadow:0 5px 15px #0002}
label{display:block;margin:13px 0 5px;font-weight:bold}
input,textarea{width:100%;padding:10px;border:1px solid #cbd5e1;border-radius:7px}
textarea{height:110px;resize:vertical}
.current{width:100%;max-height:220px;object-fit:cover;border-radius:10px;margin:10px 0}
button{margin-top:18px;padding:11px 18px;background:#4f46e5;color:white;border:0;border-radius:7px;cursor:pointer;font-weight:bold}
.delete{color:#dc2626;margin-top:8px;display:block}
</style>
</head>
<body>

<div class="container">
<div class="head"><h2>✏️ Edit Destination</h2><a href="admin_destinations.php">← Back</a></div>

<form method="POST" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?=$id?>">

<label>Destination Name</label>
<input type="text" name="name" value="<?=htmlspecialchars($dest['Name'])?>" required>

<label>GPS / Location</label>
<input type="text" name="gps" value="<?=htmlspecialchars($dest['gps'])?>">

<label>Description</label>
<textarea name="description"><?=htmlspecialchars($dest['Description'])?></textarea>

<label>Rating</label>
<input type="number" name="avg_rating" min="0" max="5" step="0.01" value="<?=$dest['avg_rating']?>">

<label>Safety</label>
<input type="text" name="peace_ind" value="<?=htmlspecialchars($dest['Peace_ind'])?>">

<label>Current Picture</label>
<?php if($dest['image']): ?>
<img class="current" src="uploads/destinations/<?=htmlspecialchars($dest['image'])?>">
<label class="delete"><input type="checkbox" name="delete_image"> Delete current picture</label>
<?php else: ?>
<p>No picture uploaded.</p>
<?php endif; ?>

<label>Replace Picture</label>
<input type="file" name="image" accept="image/jpeg,image/png,image/webp">

<button type="submit">💾 Save Changes</button>
</form>
</div>
</body>
</html>