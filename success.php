<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f8f8f8;
        }

        .error-container {
            margin: 100px auto;
            max-width: 600px;
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        .error-message {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .back-button {
            background-color: #4A8522;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin-top: 10px;
            cursor: pointer;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="form-container">
    <div class="error-container">
        <h1>Successful</h1>
        <p class="error-message">
            <?php
            // Check if the "message" parameter is set in the URL
            if (isset($_GET['message'])) {
                echo $_GET['message'];
            } else {
                echo 'An email has being sent to you.';
            }
            ?>
        </p>
        <a class="back-button" href="home.php">Back to Home</a>
    </div>
</div>
</body>
</html>
