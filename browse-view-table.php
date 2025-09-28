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
        a.btn {
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 5px;
            color: white;
        }
        .edit { background: #2196F3; }
        .delete { background: #f44336; }
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
    <h2>Books List</h2>
    <div class="top-links">
        <a href="addbook.php">➕ Add Book</a> | 
        <a href="logout.php">Logout</a>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Author</th>
            <th>Year</th>
            <th>ISBN</th>
            <th>Actions</th>
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
                        <a class="btn edit" href="edit-remove.php?action=edit&id=<?= $row['id'] ?>">Edit</a>
                        <a class="btn delete" href="edit-remove.php?action=delete&id=<?= $row['id'] ?>" onclick="return confirm('Are you sure to delete this book?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">No books found.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>
