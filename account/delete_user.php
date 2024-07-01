<?php
session_start();

// Проверяем, является ли пользователь админом
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Подключаемся к базе данных
$conn = mysqli_connect('localhost', 'root', '', 'interior_design_studio');
if ($conn->connect_error) {
    die("Ошибка подключения к базе данных: " . $conn->connect_error);
}

// Получаем ID пользователя для удаления
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Удаляем пользователя
    $sql = "DELETE FROM users WHERE id_user = $user_id";
    if ($conn->query($sql) === TRUE) {
        header("Location: users.php");
        exit;
    } else {
        echo "Ошибка при удалении пользователя: " . $conn->error;
    }
} else {
    header("Location: users.php");
    exit;
}
?>
