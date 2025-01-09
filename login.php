<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';


 if (empty($email) || empty($password)) {
    echo "Email and password are required.";
} else {
  
    $sql = "SELECT id, username, email, phone, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
       
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['username'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_phone'] = $user['phone'];
        $_SESSION['login_success'] = "Login Successful!"; 

        header("Location: index.php"); 
        exit();
    } else {
        echo "Invalid password.";
    }
} else {
    echo "No user found with that email";
}
}
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="design.css">
    <title>Login</title>
</head>
<style>
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
        }
    </style>
<body>
<?php if (isset($_SESSION['register_success'])): ?>
        <div class="success-message">
            <?php echo htmlspecialchars($_SESSION['register_success']); ?>
        </div>
        <?php unset($_SESSION['register_success']); ?>
    <?php endif; ?>
    <div class="container">
        <h1>Login</h1>
        <form method="POST" action="login.php">
    <label for="username">Email:</label>
    <input type="text" id="email" name="email" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Login</button>
</form>

<footer style="text-align: center; margin-top: 20px; font-size: 0.9em;">
    Don't have an account? <a href="register.php" style="color: #007bff; text-decoration: none;">Register</a>
</footer>
    </div>
</body>
</html>
