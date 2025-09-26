<?php
session_start();

$mysqli = new mysqli("db", "root", "rootpassword", "library_db");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

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
                header("Location: user.php");
                exit;
            }
        }
        else {
            echo "Invalid login!";
        }
    }
    else {
        echo "Both fields are required!";
    }
}
?>

<form method="post">
    <table>
        <tr>
            <td><label for="username">Username:</label></td>
            <td><input type="text" id="username" name="username"></td>
        </tr>
        <tr>
            <td><label for="password">Password:</label></td>
            <td><input type="password" id="password" name="password"></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:right;">
                <input type="submit" value="Login">
            </td>
        </tr>
    </table>
</form>