<?php
session_start();
require_once("includes/db.php");

$captchas = [
    "image1.jpg" => "Aeik2",
    "image2.jpg" => "ecb4f",
    "image3.jpg" => "7plBJ8",
    "image4.jpg" => "24qU3"
];

$message = "";

// Keep the same CAPTCHA image during one page load/submission cycle
// If no CAPTCHA has been generated yet, select one at random
if (!isset($_SESSION['captcha_image'])) {
    $_SESSION['captcha_image'] = array_rand($captchas);
    $_SESSION['captcha_answer'] = $captchas[$_SESSION['captcha_image']];
}

// Store the selected CAPTCHA image so it can be displayed on the login page
$captchaImage = $_SESSION['captcha_image'];

// Only process the login logic when the form is submitted using POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Read and trim the submitted email, password, and CAPTCHA values
    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");
    $captcha = trim($_POST["captcha"] ?? "");

    // Validate that the email is in the correct format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";
    }
    // Ensure that the password field is not empty
    elseif (empty($password)) {
        $message = "Password is required.";
    }
    // Compare the user-entered CAPTCHA with the correct answer stored in the session
    elseif (strtolower($captcha) !== strtolower($_SESSION['captcha_answer'])) {
        $message = "Incorrect CAPTCHA.";
    } else {
        // Prepare a secure SQL query to find the user by email address
        $stmt = $conn->prepare("SELECT id, name, phone, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check whether exactly one matching user account was found
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify the entered password against the hashed password stored in the database
            if (password_verify($password, $user['password'])) {
                // Store the user's details in the session to maintain login state
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['phone'] = $user['phone'];
                $_SESSION['email'] = $user['email'];

                // Clear the CAPTCHA session values after a successful login
                unset($_SESSION['captcha_image']);
                unset($_SESSION['captcha_answer']);

                // Redirect the user back to the homepage after successful authentication
                header("Location: index.php?login=success");
                exit;
            } else {
                // Display an error if the password is incorrect
                $message = "Incorrect password.";
            }
        } else {
            // Display an error if no matching account exists
            $message = "No account found with that email.";
        }

        // Close the prepared statement after use
        $stmt->close();
    }

    // Generate a new CAPTCHA after any failed login attempt
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['captcha_image'] = array_rand($captchas);
        $_SESSION['captcha_answer'] = $captchas[$_SESSION['captcha_image']];
        $captchaImage = $_SESSION['captcha_image'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Bookstore - Login</title>
    <meta name="description" content="Log in to the Online Bookstore to place book orders securely.">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header>
    <h1>Online Bookstore</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="register.php">Register</a>
    </nav>
</header>

<main>
    <h2>Login</h2>

    <?php if (!empty($message)): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </div>

        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>

        <div>
            <label>CAPTCHA Code:</label><br>
            <img src="captcha/<?php echo htmlspecialchars($captchaImage); ?>" alt="CAPTCHA">
        </div>

        <div>
            <label for="captcha">Enter CAPTCHA:</label>
            <input type="text" name="captcha" id="captcha" required>
        </div>

        <button type="submit">Login</button>
    </form>
</main>

</body>
</html>