<?php
session_start();
include("db.php"); 

$message = "";
$results = [];

// SEARCH REQUEST
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['keyword'])) {
    $keyword = mysqli_real_escape_string($conn, $_GET['keyword']);

    if (!empty($keyword)) {
        $sql = "SELECT * FROM books 
                WHERE title LIKE '%$keyword%' 
                OR author LIKE '%$keyword%' 
                OR isbn LIKE '%$keyword%' 
                OR publication_year LIKE '%$keyword%'";
        $query = mysqli_query($conn, $sql);

        if (mysqli_num_rows($query) > 0) {
            while ($row = mysqli_fetch_assoc($query)) {
                $results[] = $row;
            }
        } else {
            $message = "<p id='message' style='color:red;'>No books found for '<b>$keyword</b>'.</p>";
        }
    } else {
        $message = "<p id='message' style='color:red;'>Please enter a keyword.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Books</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            padding: 30px 50px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
            width: 700px;
        }
        input[type="text"] {
            width: 80%;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        #message {
            margin: 10px 0;
        }
        a.btn, button {
            padding: 6px 12px;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }
        .borrow { background: #4CAF50; }
        .edit { background: #2196F3; }
        .delete { background: #f44336; }
        .dashboard { background: #2196F3; margin-top: 20px; display: inline-block; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Search Books</h2>
        <form method="get" action="searchbook.php">
            <input type="text" name="keyword" placeholder="Enter title, author, ISBN, or year" value="<?php if(isset($_GET['keyword'])) echo htmlspecialchars($_GET['keyword']); ?>">
            <input type="submit" value="Search">
        </form>

        <?php
        // FOR NO RESULTS
        if (!empty($message)) {
            echo $message;
        }

        // FOR RESULTS
        if (!empty($results)) {
            echo "<table>";
            echo "<tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Year</th>
                    <th>ISBN</th>";

            if (isset($_SESSION['role']) && $_SESSION['role'] === "User") {
                echo "<th>Action</th>";
            } elseif (isset($_SESSION['role']) && $_SESSION['role'] === "Librarian") {
                echo "<th>Actions</th>";
            }

            echo "</tr>";

            foreach ($results as $book) {
                echo "<tr>";
                echo "<td>".htmlspecialchars($book['title'])."</td>";
                echo "<td>".htmlspecialchars($book['author'])."</td>";
                echo "<td>".$book['publication_year']."</td>";
                echo "<td>".htmlspecialchars($book['isbn'])."</td>";

                if (isset($_SESSION['role']) && $_SESSION['role'] === "User") {
                    echo "<td>";
                    if ($book['available'] == 1) {
                        echo "<form method='post' action='borrow.php' style='display:inline;'>
                                <input type='hidden' name='book_id' value='".$book['id']."'>
                                <button type='submit' name='borrow' class='borrow'>Borrow</button>
                              </form>";
                    } else {
                        echo "Not Available";
                    }
                    echo "</td>";
                }

                if (isset($_SESSION['role']) && $_SESSION['role'] === "Librarian") {
                    echo "<td>
                            <a class='btn edit' href='edit-remove.php?action=edit&id=".$book['id']."'>Edit</a>
                            <a class='btn delete' href='edit-remove.php?action=delete&id=".$book['id']."' onclick=\"return confirm('Are you sure to delete this book?');\">Delete</a>
                          </td>";
                }

                echo "</tr>";
            }

            echo "</table>";
        }

        // Back to Dashboard button
        if (isset($_SESSION['role'])) {
            if ($_SESSION['role'] === "User") {
                echo "<a href='user.php' class='btn dashboard'>Back to Home</a>";
            } elseif ($_SESSION['role'] === "Librarian") {
                echo "<a href='librarian.php' class='btn dashboard'>Back to Home</a>";
            }
        }
        ?>
    </div>
</body>
</html>
