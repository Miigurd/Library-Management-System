<?php
session_start();
include("db.php");

if (!isset($_GET['action']) || !isset($_GET['id'])) {
    echo "Invalid request.";
    exit;
}

$action = $_GET['action'];
$id = (int) $_GET['id'];

$result = $conn->query("SELECT * FROM books WHERE id=$id");
if ($result->num_rows === 0) {
    echo "Book not found!";
    exit;
}
$book = $result->fetch_assoc();

if ($action === "delete") {
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['confirm_delete'])) {
        $sql = "DELETE FROM books WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            header("Location: browse-view-table.php?msg=deleted");
            exit;
        } else {
            $error = "Error deleting record: " . $conn->error;
        }
    }

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Confirm Delete - Book Management</title>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <style>
            :root {
                --primary-color: #2563eb;
                --secondary-color: #1e40af;
                --danger-color: #dc2626;
                --warning-color: #f59e0b;
                --text-primary: #111827;
                --text-secondary: #6b7280;
                --bg-primary: #ffffff;
                --bg-secondary: #f9fafb;
                --border-color: #e5e7eb;
                --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
                --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
                --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
                --border-radius: 0.75rem;
            }
            * {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }
            body {
                font-family: 'Inter', sans-serif;
                background-color: var(--bg-secondary);
                color: var(--text-primary);
                line-height: 1.6;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 1rem;
            }
            .container {
                width: 100%;
                max-width: 28rem; 
            }
            .card {
                background: var(--bg-primary);
                border-radius: var(--border-radius);
                box-shadow: var(--shadow-lg);
                padding: 1.75rem;
                border: 1px solid var(--border-color);
                animation: slideIn 0.3s ease-out;
            }
            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            .header {
                text-align: center;
                margin-bottom: 1.25rem; 
                border-bottom: 1px solid var(--border-color);
                padding-bottom: 1rem;
            }
            .header h1 {
                font-size: 1.5rem; 
                font-weight: 700;
                color: var(--text-primary);
                margin-bottom: 0.25rem;
            }
            .header p {
                color: var(--text-secondary);
                font-size: 0.875rem;
            }
            .alert {
                background: linear-gradient(90deg, #fef3c7 0%, #fde68a 100%);
                border: 1px solid var(--warning-color);
                border-left: 4px solid var(--warning-color);
                border-radius: 0.5rem;
                padding: 1rem; 
                margin-bottom: 1.25rem;
                display: flex;
                align-items: center;
                gap: 0.75rem;
                font-weight: 500;
            }
            .alert-icon {
                font-size: 1.25rem;
                flex-shrink: 0;
            }
            .book-details {
                background: var(--bg-secondary);
                border-radius: 0.5rem;
                padding: 1.25rem; 
                margin-bottom: 1.5rem;
                border: 1px solid var(--border-color);
            }
            .book-details h3 {
                font-size: 1rem; 
                font-weight: 600;
                margin-bottom: 0.75rem;
                color: var(--text-primary);
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }
            .book-details dl {
                display: grid;
                grid-template-columns: 1fr 2fr;
                gap: 0.5rem 1rem; 
                font-size: 0.875rem;
            }
            .book-details dt {
                font-weight: 500;
                color: var(--text-secondary);
                text-transform: uppercase;
                letter-spacing: 0.025em;
            }
            .book-details dd {
                color: var(--text-primary);
                word-break: break-word;
            }
            .error {
                background: #fee2e2;
                border: 1px solid #ef4444;
                color: var(--danger-color);
                padding: 0.875rem; 
                border-radius: 0.5rem;
                margin-bottom: 1.25rem;
                text-align: center;
                font-weight: 500;
            }
            .button-group {
                display: flex;
                gap: 1rem;
                justify-content: center;
            }
            .btn {
                flex: 1;
                padding: 0.75rem 1.25rem; /* Smaller padding for normal size */
                border-radius: 0.5rem;
                font-size: 0.875rem;
                font-weight: 600;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                transition: all 0.2s ease;
                border: none;
                cursor: pointer;
                max-width: 10rem;
            }
            .btn-danger {
                background: var(--danger-color);
                color: white;
            }
            .btn-danger:hover {
                background: #b91c1c;
                transform: translateY(-1px);
                box-shadow: var(--shadow-md);
            }
            .btn-secondary {
                background: var(--text-secondary);
                color: white;
            }
            .btn-secondary:hover {
                background: #4b5563;
                transform: translateY(-1px);
                box-shadow: var(--shadow-md);
            }
            @media (max-width: 640px) {
                .button-group {
                    flex-direction: column;
                }
                .card {
                    padding: 1.5rem;
                }
                .book-details dl {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="card">
                <div class="header">
                    <h1>Confirm Deletion</h1>
                    <p>This action cannot be undone and will permanently remove the book.</p>
                </div>
                <div class="alert">
                    <span class="alert-icon">⚠️</span>
                    <span>Are you sure you want to delete this book from the library?</span>
                </div>
                <div class="book-details">
                    <h3>Book Information</h3>
                    <dl>
                        <dt>Title:</dt>
                        <dd><?= htmlspecialchars($book['title']) ?></dd>
                        <dt>Author:</dt>
                        <dd><?= htmlspecialchars($book['author']) ?></dd>
                        <dt>Publication Year:</dt>
                        <dd><?= $book['publication_year'] ?></dd>
                        <dt>ISBN:</dt>
                        <dd><?= htmlspecialchars($book['isbn']) ?></dd>
                    </dl>
                </div>
                <?php if (isset($error)): ?>
                    <div class="error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <div class="button-group">
                    <form method="post" style="flex: 1;">
                        <button type="submit" name="confirm_delete" value="1" class="btn btn-danger">
                            Delete Book
                        </button>
                    </form>
                    <a href="browse-view-table.php" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

if ($action === "edit") {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $title  = mysqli_real_escape_string($conn, $_POST['title']);
        $author = mysqli_real_escape_string($conn, $_POST['author']);
        $year   = (int) $_POST['year'];
        $isbn   = mysqli_real_escape_string($conn, $_POST['isbn']);

        $update = "UPDATE books SET title='$title', author='$author', publication_year='$year', isbn='$isbn' WHERE id=$id";
        if ($conn->query($update) === TRUE) {
            header("Location: browse-view-table.php?msg=updated");
            exit;
        } else {
            $error = "Error updating record: " . $conn->error;
        }
    }
    // Show edit form
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Book - Book Management</title>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <style>
            :root {
                --primary-color: #2563eb;
                --secondary-color: #1e40af;
                --success-color: #059669;
                --text-primary: #111827;
                --text-secondary: #6b7280;
                --bg-primary: #ffffff;
                --bg-secondary: #f9fafb;
                --border-color: #e5e7eb;
                --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
                --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
                --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
                --border-radius: 0.75rem;
            }
            * {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }
            body {
                font-family: 'Inter', sans-serif;
                background-color: var(--bg-secondary);
                color: var(--text-primary);
                line-height: 1.6;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 1rem;
            }
            .container {
                width: 100%;
                max-width: 28rem; /* Standard width */
            }
            .card {
                background: var(--bg-primary);
                border-radius: var(--border-radius);
                box-shadow: var(--shadow-lg);
                padding: 1.75rem; /* Reduced for normal size */
                border: 1px solid var(--border-color);
                animation: slideIn 0.3s ease-out;
            }
            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            .header {
                text-align: center;
                margin-bottom: 1.75rem; /* Balanced space */
                border-bottom: 1px solid var(--border-color);
                padding-bottom: 1rem;
            }
            .header h1 {
                font-size: 1.5rem; /* Standard */
                font-weight: 700;
                color: var(--text-primary);
                margin-bottom: 0.25rem;
            }
            .header p {
                color: var(--text-secondary);
                font-size: 0.875rem;
            }
            .error {
                background: #fee2e2;
                border: 1px solid #ef4444;
                color: var(--danger-color);
                padding: 0.875rem;
                border-radius: 0.5rem;
                margin-bottom: 1.25rem;
                text-align: center;
                font-weight: 500;
            }
            .form-group {
                margin-bottom: 1.5rem; /* Standard spacing */
            }
            .form-group label {
                display: block;
                font-size: 0.875rem;
                font-weight: 500;
                color: var(--text-primary);
                margin-bottom: 0.5rem;
                text-transform: uppercase;
                letter-spacing: 0.025em;
            }
            .form-group input {
                width: 100%;
                padding: 0.75rem 1rem; /* Normal padding */
                border: 1px solid var(--border-color);
                border-radius: 0.5rem;
                font-size: 1rem;
                transition: all 0.2s ease;
                background: var(--bg-primary);
            }
            .form-group input:focus {
                outline: none;
                border-color: var(--primary-color);
                box-shadow: 0 0 0 3px rgb(37 99 235 / 0.1);
                background: var(--bg-primary);
            }
            .btn {
                width: 100%;
                padding: 0.75rem 1.25rem; /* Smaller for normal feel */
                background: var(--primary-color);
                color: white;
                border: none;
                border-radius: 0.5rem;
                font-size: 1rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.2s ease;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                margin-top: 0.5rem;
            }
            .btn:hover {
                background: var(--secondary-color);
                transform: translateY(-1px);
                box-shadow: var(--shadow-md);
            }
            .back-link {
                display: inline-block;
                margin-top: 1.25rem;
                color: var(--primary-color);
                text-decoration: none;
                font-size: 0.875rem;
                font-weight: 500;
                transition: color 0.2s ease;
                text-align: center;
                width: 100%;
                border-top: 1px solid var(--border-color);
                padding-top: 1rem;
            }
            .back-link:hover {
                color: var(--secondary-color);
            }
            @media (max-width: 640px) {
                .card {
                    padding: 1.5rem;
                }
                .header h1 {
                    font-size: 1.25rem;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="card">
                <div class="header">
                    <h1>Edit Book</h1>
                    <p>Update the details for this book entry.</p>
                </div>
                <?php if (isset($error)): ?>
                    <div class="error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form method="post">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" id="title" name="title" value="<?= htmlspecialchars($book['title']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="author">Author</label>
                        <input type="text" id="author" name="author" value="<?= htmlspecialchars($book['author']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="year">Publication Year</label>
                        <input type="number" id="year" name="year" value="<?= $book['publication_year'] ?>" min="1800" max="<?= date('Y') + 1 ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="isbn">ISBN</label>
                        <input type="text" id="isbn" name="isbn" value="<?= htmlspecialchars($book['isbn']) ?>" required>
                    </div>
                    <button type="submit" class="btn">
                        Save Changes
                    </button>
                </form>
                <a href="browse-view-table.php" class="back-link">
                    ← Back to Books
                </a>
            </div>
        </div>
    </body>
    </html>
    <?php
}
?>