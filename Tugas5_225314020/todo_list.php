<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Koneksi ke database
$koneksi = mysqli_connect("localhost", "root", "", "todo_list");

// Ambil daftar todo dari database
$query = "SELECT * FROM todo WHERE username='{$_SESSION['username']}'";
$result = mysqli_query($koneksi, $query);
$todo_list = mysqli_fetch_all($result, MYSQLI_ASSOC);

if (isset($_POST['submit'])) {
    $todo = $_POST['todo'];

    // Masukkan todo baru ke database
    $query = "INSERT INTO todo (username, todo) VALUES ('{$_SESSION['username']}', '$todo')";
    mysqli_query($koneksi, $query);
    header("Location: todo_list.php");
    exit;
}

if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = $_GET['id'];

    // Update status atau hapus todo berdasarkan action
    if ($action == 'selesai') {
        $query = "UPDATE todo SET status='selesai' WHERE id=$id";
    } elseif ($action == 'hapus') {
        $query = "DELETE FROM todo WHERE id=$id";
    }
    mysqli_query($koneksi, $query);
    header("Location: todo_list.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To Do List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
        }

        h2 {
            text-align: center;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="text"] {
            width: 70%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 3px;
            margin-right: 10px;
        }

        input[type="submit"] {
            padding: 8px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
        }

        del {
            color: #999;
        }

        a {
            text-decoration: none;
            color: #333;
            padding: 3px 8px;
            border: 1px solid #ccc;
            border-radius: 3px;
            margin-left: 5px;
        }

        a:hover {
            background-color: #f4f4f4;
        }

    </style>
</head>
<body>
    <header>
        <h1>To Do List App</h1>
        <p><?php echo $_SESSION['username']; ?> - 225314020</p>
        <img src="foto.jpg" alt="Foto Profil"> 
    </header>
    <div class="container">
        <h2>To Do List</h2>
        <form action="todo_list.php" method="POST">
            <input type="text" name="todo" placeholder="Tambah To Do" required>
            <input type="submit" name="submit" value="Tambah">
        </form>
        <ul>
            <?php foreach ($todo_list as $todo): ?>
                <li>
                    <?php if ($todo['status'] == 'selesai'): ?>
                        <del><?php echo $todo['todo']; ?></del>
                    <?php else: ?>
                        <?php echo $todo['todo']; ?>
                    <?php endif; ?>
                    <?php if ($todo['status'] != 'selesai'): ?>
                        <a href="todo_list.php?action=selesai&id=<?php echo $todo['id']; ?>">Selesai</a>
                    <?php endif; ?>
                    <a href="todo_list.php?action=hapus&id=<?php echo $todo['id']; ?>">Hapus</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
