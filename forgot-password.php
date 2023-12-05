<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <meta charset="UTF-8">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css"> -->
       <link rel="stylesheet" href="css/style.css">  
    <style>
/* Apply styles to the entire body */
/* .form-containerrr{
    background-image: url(../images/forgot1.jpg);
}  */

 /* Style the main heading (h1) */
h3 {
    font-size: 3rem;
    color: #fff;
    position: relative;
    top: 15rem;
}

/* Style the form container */
form {
    max-width: 400px;
    margin: 0 auto;
    padding: 20px;
    position: relative;
    top: 18rem;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

/* Style form labels */
label {
    display: block;
    margin-bottom: 10px;
    font-size: 2rem;
    color: #fff;
}

/* Style the email input field */
input[type="email"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
     font-size: 1.5rem;
     border-radius:5rem;
     background-color: #6b6a67;
}

/* Style the submit button */
button {
    display: block;
    width: 100%;
    padding: 10px;
    background-color: #0b9930;
    border-radius:5rem;
    color: #fff;
    border: none;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    100% {
        transform: scale(1.05);
    }
}



    </style>
</head>
<body>
<div class="form-containerr">
    <form action="" method="post">
         <div class="logo-container">
        <img src="images\logo.png" alt="Your Logo">
    </div>
    </form>
    <h3>Forgot Password</h3>
    <form method="post" action="send-password-reset.php">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>
        <button type="submit">Send Reset Link</button>
    </form>
    
</div>
</body>
</html>

