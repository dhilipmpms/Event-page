<?php
session_start();

// Hardcoded credentials
$valid_username = "maansarovar";
$valid_password = "maansarovar@12";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header("Location: admin.php");
        exit;
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Maansarovar Events</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .login-section {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: hsl(var(--background));
        }

        .login-card {
            background: white;
            padding: 2rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow-lg);
            width: 100%;
            max-width: 400px;
            border: 1px solid hsl(var(--border));
        }

        .login-title {
            text-align: center;
            margin-bottom: 1.5rem;
            font-family: var(--font-serif);
            font-size: 1.5rem;
            color: hsl(var(--primary));
        }

        .error-msg {
            color: #ef4444;
            font-size: 0.875rem;
            text-align: center;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <section class="login-section">
        <div class="login-card">
            <h1 class="login-title">Admin Login</h1>
            <?php if ($error): ?>
                <p class="error-msg"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-input" required autofocus>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" required>
                </div>
                <button type="submit" class="btn btn-primary w-full">Login</button>
            </form>
            <div class="text-center" style="margin-top: 1rem;">
                <a href="index.html" class="contact-link">← Back to Site</a>
            </div>
        </div>
    </section>
</body>
</html>
