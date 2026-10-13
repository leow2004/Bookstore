<?php
header('Content-Type: application/json');
require_once("../includes/db.php");

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing order ID"], JSON_PRETTY_PRINT);
    exit;
}

$orderId = (int) $_GET['id'];

if ($orderId <= 0) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid order ID"], JSON_PRETTY_PRINT);
    exit;
}

$stmt = $conn->prepare("
    SELECT id, user_id, customer_name, email, phone, book_id, ordered_item, price, order_date
    FROM orders
    WHERE id = ?
");

$stmt->bind_param("i", $orderId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $order = $result->fetch_assoc();
    echo json_encode($order, JSON_PRETTY_PRINT);
} else {
    http_response_code(404);
    echo json_encode(["error" => "Order not found"], JSON_PRETTY_PRINT);
}

$stmt->close();
$conn->close();
?>