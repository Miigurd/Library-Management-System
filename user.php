<?php
session_start();
include("db.php");

// Fetch all books
$result = $conn->query("SELECT * FROM books");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Browse Books</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 40px;
        }
        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: center;
        }
        th {
            background: #4CAF50;
            color: white;
        }
        form button {
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: white;
        }
        .borrow { background: #2196F3; }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .top-links {
            text-align: center;
            margin-bottom: 20px;
        }
        .top-links a {
            margin: 0 10px;
            text-decoration: none;
            color: #333;
        }
    </style>
</head>
<body>
    <h2>Available Books</h2>
    <div class="top-links">
        <a href="searchbook.php">Search</a> | 
        <a href="logout.php">Logout</a>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Author</th>
            <th>Year</th>
            <th>ISBN</th>
            <th>Status</th>
        </tr>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['author']) ?></td>
                    <td><?= $row['publication_year'] ?></td>
                    <td><?= htmlspecialchars($row['isbn']) ?></td>
                    <td>
                        <?php if ($row['available'] == 1): ?>
                            <form method="post" action="borrow.php" style="display:inline;">
                                <input type="hidden" name="book_id" value="<?= $row['id'] ?>">
                                <button type="submit" name="borrow" class="borrow">Borrow</button>
                            </form>
                        <?php else: ?>
                            Not Available
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">No books found.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>
