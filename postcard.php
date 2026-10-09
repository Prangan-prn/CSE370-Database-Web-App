<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

$user_id=$_SESSION['user_id'];
$message="";
$message_type="";

$guides_result=$conn->query("SELECT guide_id,guide_name FROM tour_guide ORDER BY guide_name ASC");

if ($_SERVER["REQUEST_METHOD"]==="POST") {
    $suggestions=trim($_POST['suggestions']??'');
    $text=trim($_POST['Text']??'');
    $end_d=!empty($_POST['end_d'])?$_POST['end_d']:NULL;
    $guide_id=!empty($_POST['guide_id'])?(int)$_POST['guide_id']:NULL;

    if (!$text) {
        $message="Postcard text is required.";
        $message_type="error";
    } else {
        if ($guide_id===NULL) {
            $stmt=$conn->prepare("INSERT INTO postcard (suggestions,Text,end_d,guide_id,user_id) VALUES (?,?,?,NULL,?)");
            $stmt->bind_param("sssi",$suggestions,$text,$end_d,$user_id);
        } else {
            $stmt=$conn->prepare("INSERT INTO postcard (suggestions,Text,end_d,guide_id,user_id) VALUES (?,?,?,?,?)");
            $stmt->bind_param("sssii",$suggestions,$text,$end_d,$guide_id,$user_id);
        }

        if ($stmt->execute()) {
            $message="Postcard created successfully!";
            $message_type="success";
        } else {
            $message="Error creating postcard.";
            $message_type="error";
        }
        $stmt->close();
    }
}

if (isset($_GET['delete'])) {
    $postcard_id=(int)$_GET['delete'];
    $stmt=$conn->prepare("DELETE FROM postcard WHERE postcard_id=? AND user_id=?");
    $stmt->bind_param("ii",$postcard_id,$user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: postcard.php");
    exit();
}

$stmt=$conn->prepare(
    "SELECT p.postcard_id,p.suggestions,p.up_date,p.Text,p.end_d,p.status,t.guide_name
     FROM postcard p LEFT JOIN tour_guide t ON p.guide_id=t.guide_id
     WHERE p.user_id=? ORDER BY p.up_date DESC"
);
$stmt->bind_param("i",$user_id);
$stmt->execute();
$result=$stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<title>My Postcards</title>
<style>
body{font-family:Arial;margin:30px;background:#f5f7fa}.container{max-width:800px;margin:auto}
.box{background:white;padding:25px;border-radius:10px;box-shadow:0 2px 8px #0002;margin-bottom:25px}
input,textarea,select{width:100%;padding:10px;box-sizing:border-box;margin:5px 0 15px}
textarea{height:120px;resize:vertical}button{padding:10px 18px;background:#1565c0;color:white;border:0;border-radius:5px;cursor:pointer}
.postcard{border:1px solid #ddd;padding:20px;margin-bottom:15px;border-radius:8px}.delete{color:red;text-decoration:none}.success{color:green}.error{color:red}
</style>
</head>

<body>
<div class="container">
<p><a href="dashboard.php">← Back to Dashboard</a></p>
<h2>My Postcards</h2>

<div class="box">
<h3>Create a Postcard</h3>

<?php if ($message): ?>
<p class="<?php echo $message_type; ?>"><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<form method="POST">
<label>Postcard Text:</label>
<textarea name="Text" placeholder="Write your postcard message..." required></textarea>

<label>Suggestions:</label>
<input type="text" name="suggestions" placeholder="Example: Visit temples and local markets">

<label>End Date:</label>
<input type="date" name="end_d">

<label>Tour Guide:</label>
<select name="guide_id">
<option value="">-- No Guide --</option>
<?php while ($guide=$guides_result->fetch_assoc()): ?>
<option value="<?php echo $guide['guide_id']; ?>"><?php echo htmlspecialchars($guide['guide_name']); ?></option>
<?php endwhile; ?>
</select>

<button type="submit">Create Postcard</button>
</form>
</div>

<div class="box">
<h3>Saved Postcards</h3>

<?php if ($result->num_rows>0): ?>
<?php while ($row=$result->fetch_assoc()): ?>
<div class="postcard">
<h3>Postcard #<?php echo $row['postcard_id']; ?></h3>

<p><strong>Message:</strong><br><?php echo nl2br(htmlspecialchars($row['Text'])); ?></p>

<?php if (!empty($row['suggestions'])): ?>
<p><strong>Suggestions:</strong> <?php echo htmlspecialchars($row['suggestions']); ?></p>
<?php endif; ?>

<p><strong>Guide:</strong> <?php echo $row['guide_name']?htmlspecialchars($row['guide_name']):'None'; ?></p>
<p><strong>Status:</strong> <?php echo htmlspecialchars($row['status']); ?></p>

<?php if (!empty($row['end_d'])): ?>
<p><strong>End Date:</strong> <?php echo htmlspecialchars($row['end_d']); ?></p>
<?php endif; ?>

<small>Created: <?php echo htmlspecialchars($row['up_date']); ?></small><br><br>

<a class="delete" href="postcard.php?delete=<?php echo $row['postcard_id']; ?>" onclick="return confirm('Delete this postcard?');">Delete</a>
</div>
<?php endwhile; ?>
<?php else: ?>
<p>You have not created any postcards yet.</p>
<?php endif; ?>

</div>
</div>
</body>
</html>

<?php $stmt->close(); ?>