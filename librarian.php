<?php
session_start();
include("db.php");

// Ensure librarian only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Librarian") {
    header("Location: login.php");
    exit;
}

// Fetch all books
$all_books = $conn->query("SELECT * FROM books");

// Fetch borrowed books (those with return_date IS NULL)
$borrowed_books = $conn->query("
    SELECT bb.id AS borrow_id, bb.book_id, b.title, b.author
    FROM borrowed_books bb
    JOIN books b ON bb.book_id = b.id
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Books</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 40px;
        }
        table {
            width: 90%;
            margin: 20px auto;
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
        a.btn, form button {
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 5px;
            color: white;
        }
        .edit { background: #2196F3; }
        .delete { background: #f44336; }
        .return { background: #ff9800; border: none; cursor: pointer; }
        h2 {
            text-align: center;
            margin-bottom: 10px;
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
        <a href="searchbook.php">Search</a> | 
        <a href="logout.php">Logout</a>
    </div>

    <!-- All Books -->
    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Author</th>
            <th>Year</th>
            <th>ISBN</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php if ($all_books && $all_books->num_rows > 0): ?>
            <?php while($row = $all_books->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['author']) ?></td>
                    <td><?= $row['publication_year'] ?></td>
                    <td><?= htmlspecialchars($row['isbn']) ?></td>
                    <td><?= $row['available'] == 1 ? "Available" : "Borrowed" ?></td>
                    <td>
                        <a class="btn edit" href="edit-remove.php?action=edit&id=<?= $row['id'] ?>">Edit</a>
                        <a class="btn delete" href="edit-remove.php?action=delete&id=<?= $row['id'] ?>" onclick="return confirm('Are you sure to delete this book?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="7">No books found.</td></tr>
        <?php endif; ?>
    </table>

    <!-- Borrowed Books -->
    <h2>Borrowed Books</h2>
    <table>
        <tr>
            <th>Borrow ID</th>
            <th>Book ID</th>
            <th>Book Title</th>
            <th>Author</th>
            <th>Action</th>
        </tr>
        <?php if ($borrowed_books && $borrowed_books->num_rows > 0): ?>
            <?php while($row = $borrowed_books->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['borrow_id'] ?></td>
                    <td><?= $row['book_id'] ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['author']) ?></td>
                    <td>
                        <form method="post" action="return.php" style="display:inline;">
                            <input type="hidden" name="borrow_id" value="<?= $row['borrow_id'] ?>">
                            <input type="hidden" name="book_id" value="<?= $row['book_id'] ?>">
                            <button type="submit" name="return_book" class="return">Mark Returned</button>
                        </form>

                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5">No borrowed books right now.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>
