<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

$user_id = $_SESSION['user_id'];
$media_id = (int)($_GET['media_id'] ?? 0);
$message = "";
$message_type = "";

$stmt = $conn->prepare("SELECT g.Media_ID,g.image,u.name FROM gallery_media g JOIN user u ON g.user_id=u.user_id WHERE g.Media_ID=?");
$stmt->bind_param("i",$media_id);
$stmt->execute();
$media = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$media) die("Gallery image not found.");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $rating = (float)($_POST['rating'] ?? -1);

    if ($rating < 0 || $rating > 5) {
        $message = "Rating must be between 0 and 5.";
        $message_type = "error";
    } else {
        $check = $conn->prepare("SELECT feed_id FROM feedback WHERE media_id=? AND user_id=?");
        $check->bind_param("ii",$media_id,$user_id);
        $check->execute();

        if ($check->get_result()->num_rows > 0) {
            $message = "You have already rated this image.";
            $message_type = "error";
        } else {
            $insert = $conn->prepare("INSERT INTO feedback (Rating,media_id,user_id) VALUES (?,?,?)");
            $insert->bind_param("dii",$rating,$media_id,$user_id);

            if ($insert->execute()) {
                $message = "Feedback submitted successfully!";
                $message_type = "success";
            } else {
                $message = "Error submitting feedback.";
                $message_type = "error";
            }
            $insert->close();
        }
        $check->close();
    }
}

$feedback_stmt = $conn->prepare(
    "SELECT f.Rating,f.date,u.name
     FROM feedback f JOIN user u ON f.user_id=u.user_id
     WHERE f.media_id=? ORDER BY f.date DESC"
);
$feedback_stmt->bind_param("i",$media_id);
$feedback_stmt->execute();
$feedback_result = $feedback_stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<title>Give Feedback</title>
<style>
body{font-family:Arial;margin:30px;background:#f5f7fa}.container{max-width:700px;margin:auto}
.box{background:white;padding:20px;margin-bottom:20px;border-radius:10px;box-shadow:0 2px 7px #0002}
img{width:300px;max-width:100%;border-radius:8px}input{padding:10px;margin:5px 0 15px}
button{padding:10px 18px;background:#1565c0;color:white;border:0;border-radius:5px;cursor:pointer}
.success{color:green}.error{color:red}.feedback{border-top:1px solid #ddd;padding:12px 0}
</style>
</head>

<body>
<div class="container">
<p><a href="gallery.php">← Back to Gallery</a></p>

<div class="box">
<h2>Give Feedback</h2>
<img src="uploads/<?php echo htmlspecialchars($media['image']); ?>" alt="Gallery Image">

<p><strong>Uploaded by:</strong> <?php echo htmlspecialchars($media['name']); ?></p>

<?php if ($message): ?>
<p class="<?php echo $message_type; ?>"><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<form method="POST">
<label>Rating (0 to 5):</label><br>
<input type="number" name="rating" min="0" max="5" step="0.1" required><br>
<button type="submit">Submit Rating</button>
</form>
</div>

<div class="box">
<h3>Previous Ratings</h3>

<?php if ($feedback_result->num_rows > 0): ?>
<?php while ($row=$feedback_result->fetch_assoc()): ?>
<div class="feedback">
<strong><?php echo htmlspecialchars($row['name']); ?></strong>
rated <?php echo htmlspecialchars($row['Rating']); ?>/5<br>
<small><?php echo htmlspecialchars($row['date']); ?></small>
</div>
<?php endwhile; ?>
<?php else: ?>
<p>No ratings yet.</p>
<?php endif; ?>

</div>
</div>
</body>
</html>

<?php $feedback_stmt->close(); ?>