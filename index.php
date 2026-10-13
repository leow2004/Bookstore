<?php
// Start the session so login information can be accessed across the site
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Define the character encoding for the webpage -->
    <meta charset="UTF-8">

    <!-- Ensure the page displays correctly on different screen sizes -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Set the page title shown in the browser tab -->
    <title>Online Bookstore - Home</title>

    <!-- Provide a short description for search engines and accessibility tools -->
    <meta name="description" content="Browse books by audience and category in the Online Bookstore web application.">

    <!-- Link the external stylesheet used for page styling -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <!-- Header section containing the site title and navigation menu -->
    <header>
        <h1>Online Bookstore</h1>

        <!-- Navigation links for moving around the website -->
        <nav>
            <a href="index.php">Home</a>

            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- If the user is logged in, show a welcome message and logout link -->
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></span>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <!-- If the user is not logged in, show register and login links -->
                <a href="register.php">Register</a>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>

        <section class="selectors">
            <label for="audience">Select Audience:</label>
            <select id="audience">
                <option value="">-- Select --</option>
                <option value="Kids">Kids</option>
                <option value="Adults">Adults</option>
            </select>

            <label for="category">Select Category:</label>
            <select id="category">
                <option value="">-- Select --</option>
            </select>
        </section>

        <section id="books-container">
            <p>Please select a category to view books.</p>
        </section>

        <?php if (isset($_SESSION['user_id'])): ?>
            <p>You are logged in and can now place an order.</p>
        <?php else: ?>
            <p>You can browse books, but you must log in to place an order.</p>
        <?php endif; ?>

    </main>

    <script src="assets/js/main.js"></script>

</body>
</html>