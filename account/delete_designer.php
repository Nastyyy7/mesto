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

// Получаем ID дизайнера для удаления
if (isset($_GET['id'])) {
    $designer_id = $_GET['id'];

    // Удаляем дизайнера
    $sql = "DELETE FROM designers WHERE id_designer = $designer_id";
    if ($conn->query($sql) === TRUE) {
        header("Location: designers.php");
        exit;
    } else {
        echo "Ошибка при удалении дизайнера: " . $conn->error;
    }
} else {
    header("Location: designers.php");
    exit;
}
?>