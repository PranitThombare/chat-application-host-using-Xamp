<?php
// Database connection
$servername = "localhost";
$username = "root"; // default XAMPP username
$password = ""; // default XAMPP password
$dbname = "comment_app";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture the user's IP address
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $comment = $_POST['comment'];
    
    $stmt = $conn->prepare("INSERT INTO comments (user_id, comment) VALUES (?, ?)");
    $stmt->bind_param("is", $user_ip, $comment); // Change user_id to user_ip
    $stmt->execute();
    $stmt->close();
}

// Fetch comments
$result = $conn->query("SELECT * FROM comments ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Comment Application</title>
</head>
<body>
    <h1>Comments</h1>
    <form method="POST">
        <textarea name="comment" placeholder="Your comment" required></textarea>
        <button type="submit">Submit</button>
    </form>

    <h2>All Comments:</h2>
    <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
            <li><strong>User IP <?php echo $row['user_id']; ?>:</strong> <?php echo $row['comment']; ?> <em>(<?php echo $row['created_at']; ?>)</em></li>
        <?php endwhile; ?>
    </ul>
</body>
</html>

<?php
$conn->close();
?>
