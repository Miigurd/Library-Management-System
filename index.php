<?php
session_start();

$mysqli = new mysqli("db", "root", "rootpassword", "library_db");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = $mysqli->prepare("SELECT * FROM accounts WHERE username=? AND password=?");
        $sql->bind_param("ss", $username, $password);
        $sql->execute();
        $result = $sql->get_result();

        if ($row = $result->fetch_assoc()) {
            $_SESSION['role'] = $row['role'];

            if ($_SESSION['role'] == "Librarian") {
                header("Location: librarian.php");
                exit;
            }
            elseif ($_SESSION['role'] == "User") {
                $_SESSION['user_id'] = $row['id'];
                header("Location: user.php");
                exit;
            }
        }
        else {
            $message = "<p id='message' style='color:red;'>Invalid login!</p>";
        }
    }
    else {
        $message = "<p id='message' style='color:red;'>Both fields are required!</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            padding: 30px 200px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        input[type="submit"] {
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        p {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form method="post">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username"><br>

            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password"><br>

            <input type="submit" value="Login">
        </form>

        <?php
        if (!empty($message)) {
            echo $message;
        }
        ?>
    </div>

    <script>
        window.onload = function() {
            const msg = document.getElementById('message');
            if (msg) {
                setTimeout(() => { msg.style.display = 'none'; }, 1500);
            }
        }
    </script>
</body>
</html>
