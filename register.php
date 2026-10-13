<?php
session_start();
require_once("includes/db.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if (!preg_match("/^[a-zA-Z ]+$/", $name)) {
        $message = "Name must contain letters only.";
    } elseif (!preg_match("/^[0-9]{10}$/", $phone)) {
        $message = "Phone number must be exactly 10 digits.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";
    } elseif (empty($password)) {
        $message = "Password is required.";
    } else {
        $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->store_result();

        // Check whether an account with the entered email address already exists
        if ($checkStmt->num_rows > 0) {
            $message = "This email is already registered.";
        } else {
            // Hash the password before storing it in the database for security
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Prepare an SQL statement to insert the new user record safely
            $insertStmt = $conn->prepare("INSERT INTO users (name, phone, email, password) VALUES (?, ?, ?, ?)");

            // Bind the user input values to the prepared statement
            $insertStmt->bind_param("ssss", $name, $phone, $email, $hashedPassword);

            // Execute the statement and check whether the insertion was successful
            if ($insertStmt->execute()) {
                $message = "Registration successful.";
            } else {
                $message = "Registration failed. Please try again.";
            }

            // Close the prepared statement to free resources
            $insertStmt->close();
        }

        $checkStmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Bookstore - Register</title>
    <meta name="description" content="Create an account for the Online Bookstore web application.">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://unpkg.com/react@18/umd/react.development.js" crossorigin></script>
    <script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js" crossorigin></script>
</head>
<body>

<header>
    <h1>Online Bookstore</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
    </nav>
</header>

<main>
    <h2>Register</h2>

    <?php if (!empty($message)): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <div id="react-form"></div>
</main>

<script src="assets/js/react-validation.js"></script>
</body>
</html>