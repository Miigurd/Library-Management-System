<?php
session_start();
include("db.php");

// Only librarians allowed
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Librarian") {
    header("Location: index.php");
    exit;
}

if (isset($_POST['return_book'])) {
    $borrow_id = intval($_POST['borrow_id']);
    $book_id   = intval($_POST['book_id']);

    // Delete borrowed record
    $conn->query("DELETE FROM borrowed_books WHERE id = $borrow_id");

    // Make book available again
    $conn->query("UPDATE books SET available = 1 WHERE id = $book_id");

    $_SESSION['message'] = "Book returned successfully!";
}

header("Location: librarian.php");
exit;
