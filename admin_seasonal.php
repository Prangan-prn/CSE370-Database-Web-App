<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}
include 'db.php';

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $dest_id   = (int)($_POST['dest_id'] ?? 0);
    $bst_month = trim($_POST['bst_month'] ?? '');
    $reason    = trim($_POST['reason'] ?? '');
    $cost      = (float)($_POST['cost'] ?? 0);

    if ($dest_id > 0 && !empty($bst_month)) {
        
        $stmt = $conn->prepare("INSERT INTO seasonal_advisory (Dest_id, Bst_month, Reason, trans_cost) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("issd", $dest_id, $bst_month, $reason, $cost);
        
        if ($stmt->execute()) {
            $message = "Seasonal Advisory added successfully!";
            $message_type = "success";
        } else {
            $message = "Database Error: " . $stmt->error;
            $message_type = "error";
        }
        $stmt->close();
    } else {
        $message = "Please fill in Destination and Season/Time.";
        $message_type = "error";
    }
}

$dest_result = $conn->query("SELECT Dest_ID, Name FROM destination");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Seasonal Advisory</title>
    <style>
        * { box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background: #f5f7fa; margin: 0; padding: 20px; color: #1e293b; }
        .container { max-width: 500px; margin: 30px auto; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        h2 { color: #4f46e5; margin-top: 0; }
        label { display: block; margin-top: 12px; font-weight: bold; font-size: 14px; }
        select, input, textarea, button { width: 100%; padding: 10px; margin-top: 6px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; }
        button { background: #4f46e5; color: white; border: none; font-weight: bold; cursor: pointer; margin-top: 18px; }
        button:hover { background: #4338ca; }
        .success { background: #dcfce7; color: #15803d; padding: 10px; border-radius: 6px; margin-bottom: 12px; }
        .error { background: #fee2e2; color: #dc2626; padding: 10px; border-radius: 6px; margin-bottom: 12px; }
        a { display: inline-block; margin-bottom: 12px; color: #4f46e5; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <a href="admin.php">← Back to Admin Dashboard</a>
    <h2>➕ Add Seasonal Advisory</h2>

    <?php if($message): ?>
        <div class="<?=$message_type?>"><?=htmlspecialchars($message)?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Select Destination:</label>
        <select name="dest_id" required>
            <option value="">-- Choose Destination --</option>
            <?php if($dest_result && $dest_result->num_rows > 0): ?>
                <?php while($dest = $dest_result->fetch_assoc()): ?>
                    <option value="<?=$dest['Dest_ID']?>"><?=htmlspecialchars($dest['Name'])?></option>
                <?php endwhile; ?>
            <?php endif; ?>
        </select>

        <label>Best Season / Time:</label>
        <input type="text" name="bst_month" placeholder="e.g. Summer" required>

        <label>Reason to Visit:</label>
        <textarea name="reason" rows="3" placeholder="e.g. Vibrant street festivals and clear skies."></textarea>

        <label>Estimated Transportation Cost ($):</label>
        <input type="number" step="0.01" name="cost" placeholder="e.g. 150.00">

        <button type="submit">Save Advisory</button>
    </form>
</div>

</body>
</html>