<?php
session_start();
include 'db.php';

$message="";

if($_SERVER["REQUEST_METHOD"]=="POST"){
$email=trim($_POST['email']);$pass=$_POST['pass'];
$stmt=$conn->prepare("SELECT user_id,name,pass,type FROM user WHERE email=?");
$stmt->bind_param("s",$email);$stmt->execute();
$result=$stmt->get_result();

if($result->num_rows===1){
$user_data=$result->fetch_assoc();
if(password_verify($pass,$user_data['pass'])){
$_SESSION['user_id']=$user_data['user_id'];
$_SESSION['user_name']=$user_data['name'];
$_SESSION['user_type']=$user_data['type'];
header("Location: dashboard.php");exit();
}else$message="Invalid password!";
}else$message="No account found with that email!";

$stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<style>
*{box-sizing:border-box}body{font-family:Arial;margin:0;background:linear-gradient(135deg,#eef2ff,#f0fdf4);color:#1e293b}
.container{max-width:450px;margin:70px auto;padding:20px}
.head{background:linear-gradient(135deg,#4f46e5,#7c3aed);color:white;padding:25px;border-radius:18px;text-align:center;margin-bottom:20px;box-shadow:0 6px 16px #0002}
.head h2{margin:0}
.box{background:white;padding:25px;border-radius:16px;box-shadow:0 6px 16px #0002}
input{width:100%;padding:11px;border:1px solid #cbd5e1;border-radius:7px;margin:8px 0 15px}
input:focus{outline:none;border-color:#4f46e5}
button{padding:10px 18px;background:#4f46e5;color:white;border:0;border-radius:7px;cursor:pointer;width:100%}
button:hover{background:#3730a3}
.error{background:#fef2f2;color:#dc2626;padding:10px;border-radius:7px}
a{color:#4f46e5;text-decoration:none;font-weight:bold}
a:hover{color:#3730a3}
</style>
</head>

<body>
<div class="container">

<div class="head">
<h2>🔐 Login</h2>
</div>

<div class="box">

<?php if(!empty($message)): ?>
<p class="error"><?=htmlspecialchars($message)?></p>
<?php endif;?>

<form method="POST" action="login.php">
<input type="email" name="email" placeholder="Email Address" required>
<input type="password" name="pass" placeholder="Password" required>
<button type="submit">Login</button>
</form>

<p>Don't have an account? <a href="signup.php">Sign Up</a></p>

</div>
</div>
</body>
</html>
```
