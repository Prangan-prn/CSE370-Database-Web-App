<?php
session_start();
if(!isset($_SESSION['user_id'])){header("Location: login.php");exit();}
include 'db.php';

$message=""; $error=false;

if($_SERVER["REQUEST_METHOD"]=="POST"){
    if(!isset($_FILES['image'])){$message="Please select an image."; $error=true;}
    else{
        $file=$_FILES['image'];
        $types=['image/jpeg','image/png','image/gif','image/webp'];
        $type=mime_content_type($file['tmp_name']);

        if($file['error']!==UPLOAD_ERR_OK){$message="There was an error uploading the image."; $error=true;}
        elseif(!in_array($type,$types)){ $message="Only JPG, PNG, GIF and WEBP images are allowed."; $error=true;}
        elseif($file['size']>5*1024*1024){$message="Image must be smaller than 5MB."; $error=true;}
        else{
            $ext=strtolower(pathinfo($file['name'],PATHINFO_EXTENSION));
            $filename=uniqid('gallery_',true).'.'.$ext;
            $dir=__DIR__.'/uploads/';
            $path=$dir.$filename;

            if(!is_dir($dir)) mkdir($dir,0755,true);

            if(move_uploaded_file($file['tmp_name'],$path)){
                $user_id=$_SESSION['user_id'];
                $stmt=$conn->prepare("INSERT INTO gallery_media (image,user_id) VALUES (?,?)");
                $stmt->bind_param("si",$filename,$user_id);

                if($stmt->execute()) $message="Image uploaded successfully!";
                else{$message="Database error while saving image."; $error=true; unlink($path);}
                $stmt->close();
            }else{$message="Could not save the uploaded image."; $error=true;}
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Upload Gallery Image</title>
<style>
body{font-family:Arial,sans-serif;margin:30px;background:#f5f7fa}
.box{max-width:500px;background:white;padding:25px;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.1)}
input{margin:15px 0}
button{background:#1565c0;color:white;border:0;padding:10px 18px;border-radius:5px;cursor:pointer}
.message{color:<?=$error?'red':'green'?>}
</style>
</head>
<body>
<div class="box">
<h2>Upload Gallery Image</h2>
<p><a href="gallery.php">Back to Gallery</a></p>

<?php if($message): ?>
<p class="message"><?=htmlspecialchars($message)?></p>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
<label>Select Image:</label><br>
<input type="file" name="image" accept="image/jpeg,image/png,image/gif,image/webp" required><br>
<button type="submit">Upload Image</button>
</form>
</div>
</body>
</html>