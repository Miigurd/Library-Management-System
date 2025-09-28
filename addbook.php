<?php
session_start();
include("db.php");

$message = "";

// FORM SUBMISSION 
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['title'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $year = (int) $_POST['publication_year'];
    $isbn = mysqli_real_escape_string($conn, $_POST['isbn']);

    if (!empty($title) && !empty($author) && !empty($year) && !empty($isbn)) {
        $sql = "INSERT INTO books (title, author, publication_year, isbn) 
                VALUES ('$title', '$author', '$year', '$isbn')";
        if (mysqli_query($conn, $sql)) {
            $message = "<p id='message' style='color:green;'>Book added successfully!</p>";
        } else {
            $message = "<p id='message' style='color:red;'>Error: " . mysqli_error($conn) . "</p>";
        }
    } else {
        $message = "<p id='message' style='color:red;'>All fields are required.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Book</title>
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
        input[type="text"], input[type="number"] {
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
        <h2>Add New Book</h2>
        <form method="post" action="">
            <label>Title:</label><br>
            <input type="text" name="title" required><br>

            <label>Author:</label><br>
            <input type="text" name="author" required><br>

            <label>Publication Year:</label><br>
            <input type="number" name="publication_year" min="1500" max="2100" required><br>

            <label>ISBN:</label><br>
            <input type="text" name="isbn" required><br>

            <input type="submit" value="Add Book">
        </form>

        <br>
        <a href="librarian.php">
        <button type="button" style="padding:10px 20px; border:none; border-radius:5px; background-color:#2196F3; color:white; cursor:pointer;">
            Back to Home
        </button>
        </a>
        
        <?php
        //MESSAGE DISPLAY AFTER BUTTON CLICKED
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            echo $message;
        }
        ?>
    </div>

    <script>
        // JS HIDE MESSAGE AFTER 1.5 SECONDS
        window.onload = function() {
            const msg = document.getElementById('message');
            if (msg) {
                setTimeout(() => { msg.style.display = 'none'; }, 1500);
            }
        }
    </script>
</body>
</html>
