<?php
session_start();
require_once("includes/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userId = $_SESSION['user_id'];
    $customerName = $_SESSION['name'];
    $email = $_SESSION['email'];
    $phone = $_SESSION['phone'];

    $bookId = trim($_POST['book_id'] ?? '');
    $orderedItem = trim($_POST['ordered_item'] ?? '');
    $price = trim($_POST['price'] ?? '');

    if (empty($bookId) || empty($orderedItem) || empty($price)) {
        $message = "Invalid order details.";
    } else {
        $stmt = $conn->prepare("INSERT INTO orders (user_id, customer_name, email, phone, book_id, ordered_item, price) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssisd", $userId, $customerName, $email, $phone, $bookId, $orderedItem, $price);

        if ($stmt->execute()) {
            $message = "Order placed successfully.";
        } else {
            $message = "Order failed. Please try again.";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - Online Bookstore</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header>
    <h1>Online Bookstore</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<main>
    <h2>Order Confirmation</h2>
    <p><?php echo htmlspecialchars($message); ?></p>

    <?php if (!empty($orderedItem) && !empty($price)): ?>
        <p><strong>Book:</strong> <?php echo htmlspecialchars($orderedItem); ?></p>
        <p><strong>Price:</strong> £<?php echo htmlspecialchars($price); ?></p>
    <?php endif; ?>

    <p><a href="index.php">Return to homepage</a></p>
</main>

</body>
</html>