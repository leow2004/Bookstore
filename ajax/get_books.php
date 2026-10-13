<?php
session_start();
require_once("../includes/db.php");

$audience = $_GET['audience'] ?? '';
$category = $_GET['category'] ?? '';

if (empty($audience) || empty($category)) {
    echo "<p>Invalid selection.</p>";
    exit;
}

$stmt = $conn->prepare("SELECT id, title, image, price FROM books WHERE audience = ? AND category = ?");
$stmt->bind_param("ss", $audience, $category);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<div class='book-grid'>";
    while ($row = $result->fetch_assoc()) {
        $bookId = htmlspecialchars($row['id']);
        $title = htmlspecialchars($row['title']);
        $image = htmlspecialchars($row['image']);
        $price = htmlspecialchars($row['price']);

        echo "<div class='book-card'>";

        if (!empty($image)) {
            echo "<img src='$image' alt='$title'>";
        }

        echo "<h3>$title</h3>";
        echo "<p>Price: £$price</p>";

        if (isset($_SESSION['user_id'])) {
            echo "
                <form method='POST' action='order.php'>
                    <input type='hidden' name='book_id' value='$bookId'>
                    <input type='hidden' name='ordered_item' value='$title'>
                    <input type='hidden' name='price' value='$price'>
                    <button type='submit'>Order</button>
                </form>
            ";
        } else {
            echo "<p><em>Login to place an order</em></p>";
        }

        echo "</div>";
    }
    echo "</div>";
} else {
    echo "<p>No books found for this category.</p>";
}

$stmt->close();
$conn->close();
?>