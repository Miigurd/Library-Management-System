<?php
session_start();
include("db.php");

// Ensure only logged-in users can borrow
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['borrow']) && isset($_POST['book_id'])) {
    $book_id = intval($_POST['book_id']);
    $user_id = intval($_SESSION['user_id']);

    // Check if book is still available
    $check = $conn->query("SELECT available FROM books WHERE id = $book_id");
    $book = $check->fetch_assoc();

    if ($book && $book['available'] == 1) {
        // Mark book as borrowed
        $conn->query("UPDATE books SET available = 0 WHERE id = $book_id");

        // Insert into borrowed_books log
        $conn->query("INSERT INTO borrowed_books (user_id, book_id) VALUES ($user_id, $book_id)");

        $_SESSION['message'] = "Book borrowed successfully!";
    } else {
        $_SESSION['message'] = "This book is not available.";
    }
}

header("Location: user.php");
exit;
