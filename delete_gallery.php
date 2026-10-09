<?php
session_start();
if(!isset($_SESSION['user_id'])){header("Location: login.php");exit();}
include 'db.php';

if($_SERVER['REQUEST_METHOD']!=='POST'){header("Location: gallery.php");exit();}

$media_id=$_POST['media_id']??null;
if(!$media_id||!is_numeric($media_id)){header("Location: gallery.php");exit();}

$user_id=$_SESSION['user_id'];
$stmt=$conn->prepare("SELECT image FROM gallery_media WHERE Media_ID=? AND user_id=?");
$stmt->bind_param("ii",$media_id,$user_id);
$stmt->execute();
$result=$stmt->get_result();

if($result->num_rows===1){
    $image=$result->fetch_assoc()['image'];
    $stmt->close();

    $stmt=$conn->prepare("DELETE FROM gallery_media WHERE Media_ID=? AND user_id=?");
    $stmt->bind_param("ii",$media_id,$user_id);
    $stmt->execute();
    $stmt->close();

    $file=__DIR__."/uploads/".$image;
    if(file_exists($file)) unlink($file);
}else{
    $stmt->close();
}

header("Location: gallery.php");
exit();
?>