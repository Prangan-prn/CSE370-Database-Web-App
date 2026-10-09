<?php
include 'db.php';

$message="";

if(isset($_POST['register'])){
$name=trim($_POST['name']??'');
$email=trim($_POST['email']??'');
$pass=$_POST['pass']??'';

if($name===''||$email===''||$pass==='')$message="All fields are required.";
elseif(!filter_var($email,FILTER_VALIDATE_EMAIL))$message="Please enter a valid email address.";
else{
$type="tourist";$hashed_password=password_hash($pass,PASSWORD_DEFAULT);
$stmt=$conn->prepare("INSERT INTO user (name,email,pass,type) VALUES(?,?,?,?)");
$stmt->bind_param("ssss",$name,$email,$hashed_password,$type);

if($stmt->execute()){ $stmt->close();header("Location: login.php");exit();}
elseif($stmt->errno==1062)$message="An account with this email already exists.";
else$message="Error: ".$stmt->error;

$stmt->close();
}
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Sign Up</title>
<style>
*{box-sizing:border-box}body{font-family:Arial;margin:0;background:linear-gradient(135deg,#eef2ff,#f0fdf4);color:#1e293b}
.container{max-width:450px;margin:60px auto;padding:20px}
.head{background:linear-gradient(135deg,#4f46e5,#7c3aed);color:white;padding:25px;border-radius:18px;text-align:center;margin-bottom:20px;box-shadow:0 6px 16px #0002}
.head h2{margin:0}
.box{background:white;padding:25px;border-radius:16px;box-shadow:0 6px 16px #0002}
input{width:100%;padding:11px;border:1px solid #cbd5e1;border-radius:7px;margin:8px 0 15px}
input:focus{outline:none;border-color:#4f46e5}
button{width:100%;padding:10px 18px;background:#4f46e5;color:white;border:0;border-radius:7px;cursor:pointer}
button:hover{background:#3730a3}
.error{background:#fef2f2;color:#dc2626;padding:10px;border-radius:7px}
a{color:#4f46e5;text-decoration:none;font-weight:bold}
a:hover{color:#3730a3}
</style>
</head>

<body>
<div class="container">

<div class="head">
<h2>📝 Sign Up</h2>
</div>

<div class="box">

<?php if(!empty($message)): ?>
<p class="error"><?=htmlspecialchars($message)?></p>
<?php endif;?>

<form method="POST">
<input type="text" name="name" placeholder="Full Name" required>
<input type="email" name="email" placeholder="Email Address" required>
<input type="password" name="pass" placeholder="Password" required>
<button type="submit" name="register">Register</button>
</form>

<p>Already have an account? <a href="login.php">Login</a></p>

</div>
</div>
</body>
</html>
```
