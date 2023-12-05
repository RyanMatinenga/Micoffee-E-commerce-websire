<?php

$token = $_GET["token"];

$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "/config.php";

$sql = "SELECT * FROM users
        WHERE reset_token_hash = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user === null) {
    die("token not found");
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
    die("token has expired");
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .error-message {
            color: red;
            font-size: 0.8em;
        }
    </style>
</head>
<body>
<div class="form-container">
     <div class="logo-container">
        <img src="images\logo.png" alt="Your Logo">
    </div>
    <h3>Reset Password</h3>

    <form method="post" action="process-reset-password.php">

        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

        <label for="password">New password</label>
        <input type="password" id="password" name="password">
        <div class="error-message" id="password-error"></div>

        <label for="password_confirmation">Repeat password</label>
        <input type="password" id="password_confirmation" 
               name="password_confirmation">
        <div class="error-message" id="confirm-password-error"></div>

        <button>Send</button>
    </form>
</div>

<script>
    document.querySelector('form').addEventListener('submit', function(event) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        const passwordError = document.getElementById('password-error');
        const confirmPasswordError = document.getElementById('confirm-password-error');
        let isValid = true;

        passwordError.textContent = '';
        confirmPasswordError.textContent = '';

        if (password.length < 8) {
            passwordError.textContent = 'Password must be at least 8 characters.';
            isValid = false;
        }

        if (password !== confirmPassword) {
            confirmPasswordError.textContent = 'Passwords must match.';
            isValid = false;
        }

        if (!isValid) {
            event.preventDefault();
        }
    });
</script>
</body>
</html>
